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