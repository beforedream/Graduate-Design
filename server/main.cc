#include "WebSocketServerOperate.h"
int main(void){
    WebSocketServerOperate serverWebSocketServerOperate;
    serverWebSocketServerOperate.Init(SERVER_PORT);
    serverWebSocketServerOperate.StartWork();
    while(true){
        usleep(200000);
    }
    serverWebSocketServerOperate.StopWork();
    serverWebSocketServerOperate.Uninit();
}