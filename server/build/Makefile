#g++ -o server ../main.cc -I /home/litao/websocketpp -lboost_system -lboost_chrono
#!/bin/sh
documentRoot=`pwd`

libdir=libwebsockets

cd $libdir/build/bin
./libwebsockets-test-server --resource_path=$documentRoot/test-server
