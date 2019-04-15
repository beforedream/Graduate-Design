#ifndef WebSocketServerOperate_h
#define WebSocketServerOperate_h

#include <boost/algorithm/string.hpp>
#include <string>
#include <vector>
#include <iostream>
#include <list>
#include <unistd.h>
#include <boost/thread.hpp>
#include <websocketpp/config/asio_no_tls.hpp>
#include <websocketpp/server.hpp>

#define Base_Uri_Length 128

#define SERVER_PORT 8848

struct NameAndValue{
    std::string strName;
    std::string strValue;
};

typedef websocketpp::server<websocketpp::config::asio> server;
using websocketpp::lib::placeholders::_1;
using websocketpp::lib::placeholders::_2;

typedef server::message_ptr message_ptr;

class WebSocketServerOperate{
public:
    WebSocketServerOperate(void);
    ~WebSocketServerOperate(void);

    int Init(unsigned short usPort, const char *pBaseUri="/ws");
    int Uninit(void);
    int StartWork(void);
    int StopWork(void);

protected: 
    int ThreadProcess(void);
    void InsertClientConnection(websocketpp::connection_hdl hdl);
    void DeleteClientConnection(websocketpp::connection_hdl hdl);

public:
    bool validate(server *s, websocketpp::connection_hdl hdl);
    
    void on_http(server *s, websocketpp::connection_hdl hdl);
    void on_fail(server *s, websocketpp::connection_hdl hdl);

    void on_open(server *s, websocketpp::connection_hdl);
    void on_close(server *s, websocketpp::connection_hdl hdl);
    void on_message(server *s, websocketpp::connection_hdl hdl, message_ptr msg);

public:
    static int StringSplit(std::vector<std::string> &dst, const std::string &src, const std::string &separator);
    static std::string &StringTrim(std::string &str);
    static bool GetRequestCommandAndParameter(std::string strUri, std::string &strRequestOperateCommand, std::vector<NameAndValue> &listRequestOperateParameter);

protected:
    unsigned short m_usPort;
    char m_szBaseUri[Base_Uri_Length];
    server m_server;
    boost::thread *m_threadMain;
    bool m_bThreadExit;
    std::list<websocketpp::connection_hdl> m_listClientConnection;
};
#endif