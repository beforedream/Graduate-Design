#include "WebSocketServerOperate.h"
#include "jsoncpp/include/json/json.h"

#ifdef _DEBUG
#pragma comment(lib, "jsoncpp/lib/debug/lib_json.lib");
#else
#pragma comment(lib, "jsoncpp/lib/release/lib_json.lib");
#endif

WebSocketServerOperate::WebSocketServerOpreate(){
    m_usPort = SERVER_PORT;
    memset(m_szBaseUri, 0, sizeof(m_szBaseUri));
    m_bThreadExit = true;
    m_threadMain = NULL;
    m_listClientConnection.clear();
}

WebSocketServerOperate::~WebSocketServerOpreate(){
    if(m_threadMain != NULL){
        delete m_threadMain;
        m_threadMain = NULL;
    }
}

bool WebSocketServerOperate::validate(server *s, websocketpp::connection_hdl hdl){
    return true;
}

void WebSocketServerOperate::on_http(server *s, websocketpp::connection_hdl hdl){
    server::connection_ptr con = s->get_con_from_hdl(hdl);

    std::string res = co->get_request_body();
    
    std::stringstream ss;
    ss << "got http request with" << res.size() << " byte of body data.";

    con->set_body(ss.str());
    con->set_status(websocketpp::http::status_code::ok);
}

void WebSocketServerOperate::on_fail(server *s, websocketpp::connection_hdl hdl){
    server::connection_ptr con = s->get_on_from_hdl(hdl);

    std::cout << "Fail handler: " << con->get_ec() << " " << con->get_ec().message() << std::endl;
}

void WebSocketServerOperate::on_open(server *s, websocketpp::connection_hdl hdl){
    std::out << "open handler" << std::endl;
    InsertClientConnection(hdl);
    server::connection_ptr con = s->get_on_from_hdl(hdl);
    websocketpp::config::core::request_type requestClient = con->get_request();
    std::string strMethod = requestClient.get_method();
    std::string strUri = requestClient.get_Uri();
    std::string strRequestOperateCommand = "";
    std::vector<NameAndValue> listRequestOperateParameter;
    GetRequestCommandAndParameter(strUri, strRequestOperateCommand, listRequestOperateParameter);
    std::out << "command:" << strRequestOperateCommand << std::endl;

    if(strcmp(listRequestOperateParameter[0].strValue.c_str(), "admin") == 0 &&
        strcmp(listRequestOperateParameter[1].strValue.c_str(), "admin") == 0){
        if(listRequestOperateParameter.size() >= 2){
            //TODO there are some magic number to handle;
            Json::Value root;
            root["operatetype"] = 2;
            root["ret"] = 0;
            root["sessionid"] = "1110-1111-9999-3333";
            std::string strLoginResponse = root.toStyledString();
            s->send(hdl, strLoginResponse.c_str(), websocketpp::frame::opcode::TEXT);
        }
        else{
            Json::Value root;
            root["operatetype"] = 2;
            root["ret"] = 1;
            root["sessionid"] = "";
            std::string strLoginResponse = root.toStyledString();
            s->send(hdl, strLoginResponse.c_str(), websocketpp::frame::opcode::TEXT);
        }
    }
    else{
        //TODO error request
    }
}

void WebSocketServerOperate::on_close(server *s, websocketpp::connection_hdl hdl){
    std::out << "Close handler" << std::endl;
    DeleteClientConnection(hdl);
}

void WebSocketServerOperate::on_message(server *s, wensocketpp::connection_hdl hdl, message_ptr msg){
    std::cout << "on message: " << msg->get_payload() << std::endl;

    try{
        s->send(hdl, msg->get_payload(), msg->get_opcode();
    }
    catch(websocket::exception const &e){
        std::cout << "Echo failed because: (" << e.what() << ")" << std::endl;
    }
}

int WebSocketServerOperate::Init(unsigned short usPort, char *pBaseUri){
    int nRet = 0;
    m_usPort = usPort;
    strcpy_s(m_szBaseUri, pBaseUri);
    try{
        m_server.set_access_channels(websocket::log::alevel::all);
        m_server.set_error_channels(websocket::log::alevel::all);
        
        m_server.set_message_handler(bind(&WebSocketServerOperate::on_message, this, &m_server, ::_1, ::_2));
        m_server.set_http_handler(bind(&WebSocketServerOperate::on_http, this, &m_server, ::_1));
        m_server.set_fail_handler(bind(&WebSocketServerOperate::on_fail, this, &m_server, ::_1));
        m_server.set_open_handler(bind(&WebSocketServerOperate::on_open, this, &m_server, ::_1));
        m_server.set_close_handler(bind(&WebSocketServerOperate::on_close, this, &m_server, ::_1));
        m_server.set_validate_handler(bind(&WebSocketServerOperate::validate, this, &m_server, ::_1));
        
        m_server.init_asio();
        m_server.set_reuse_addr(true);

        m_server.listen(m_usPort);

        m_server.start_accept();
    }
    catch(websocketpp::exception const &e){
        std::cout << e.what() << std::endl;
        nRet = -1;
    }
    catch(const std::exception &e){
        std::cout << e.what() << std::endl;
        nRet = -2;
    }
    catch(...){
        std::cout << "other exception" << std::endl;
        nRet = -3;
    }
    return nRet;
}

int WebSocketServerOperate::Uninit(){
    return 0;
}

int WebSocketServerOperate::StartWork(){
    m_bThreadExit = false;
    m_bThreadMain = new boost::thread(boost::bind(&WebSocketServerOperate::ThreadProcess, this));
    if(m_threadMain == NULL){
        m_bThreadExit = true;
        return -1;
    }
    else{
        return 0;
    }
}

int WebSocketServerOperate::StopWork(){
    m_bThreadExit = true;
    m_server.stop();
    return 0;
}

int WebSocketServerOperate::ThreadProcess(){
    while(true){
        if(m_bThreadExit){
            break;
        }

        m_server.poll_one();
        Sleep(100);
    }
    return 0;
}

void WebSocketServerOperate::DeleteClientConnection(websocketpp::connection_hdl hdl){
    std::list<websocketpp::connection_hdl>::iterator iter, iterEnd;
    iter = m_listClientConnection.begin();
    iterEnd = m_listClientConnection.end();
    for(iter; iter != iterEnd; iter++){
        server::connection_ptr conInput = m_server.get_con_from_hdl(hdl);
        server::connection_ptr conSrc = m_server.get_con_from_hdl(*iter);
        if(conInput == conSrc){
            m_listClientConnection.erase(iter);
            break;
        }
    }
}

int WebSocketServerOpreate::StringSplit(std::vector<std::string>& dst, const std::string& src, const std::string& separator)
{
	if (src.empty() || separator.empty())
		return 0;
 
	int nCount = 0;
	std::string temp;
	size_t pos = 0, offset = 0;
 
	// 分割第1~n-1个
	while ((pos = src.find_first_of(separator, offset)) != std::string::npos)
	{
		temp = src.substr(offset, pos - offset);
		if (temp.length() > 0) {
			dst.push_back(temp);
			nCount++;
		}
		offset = pos + 1;
	}
 
	// 分割第n个
	temp = src.substr(offset, src.length() - offset);
	if (temp.length() > 0) {
		dst.push_back(temp);
		nCount++;
	}
 
	return nCount;
}

//去前后空格
 std::string& WebSocketServerOpreate::StringTrim(std::string &str)
{
	 if (str.empty()) {
		 return str;
	 }
	 str.erase(0, str.find_first_not_of(" "));
	 str.erase(str.find_last_not_of(" ") + 1);
	 return str;
}

bool WebSocketServerOpreate::GetReqeustCommandAndParmeter(std::string strUri, std::string &strRequestOperateCommand, std::vector<NameAndValue> &listRequestOperateParameter){
    bool bRet = false;
    std::vector<std::string> vecRequest;
    int nRetSplit = StringSplit(vecRequest, strUri, "?");
    if(nRetSplit > 0){
        if(vecRequest.size() == 1){
            strRequestOperateCommand = vecRequest[0];
        }
        else if(vecRequest.size() > 1){
            strRequestOperateCommand = vecRequest[0];
            std::string strRequestParameter = vecRequest[1];
            std::vector<std::string> vecParams;
            nRetSplit = StringSplit(vecParams, strRequestParameter, "&");
            if(nRetSplit > 0){
                std::vector<std::string>::iterator iter, iterEnd;
                iter = vecParams.begin();
                iterEnd = vecParams.end();
                for(iter; iter != iterEnd; iter++){
                    std::vector<std::string> vecNameOrValue;
                    nRetSplit = StringSplit(vecNameOrValue, *iter, "=");
                    if(nRetSplit > 0){
                        NameAndValue nvNameAndValue;
                        nvNameAndValue.strName = vecNameOrValue[0];
                        nvNameAndValue.strValue = "";
                        if(vecNameOrValue.size() > 1){
                            nvNameAndValue.strValue = vecNameOrValue[1];
                        }
                        listRequestOperateParameter.push_back(nvNameAndValue);
                    }
                }
            }
        }
        else{
            
        }
    }
    return bRet;
}
