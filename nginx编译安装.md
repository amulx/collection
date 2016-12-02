第一步：nginx依赖包的安装

yum install zlib-devel pcre-devel openssl-libs openssl -y                    
gcc gcc-c++ 

yum install openssl openssl-devel  pcre pcre-devel zlib zlib-devel -y


yum install pcre-devel zlib-devel openssl openssl-devel -y  //������




第二步：nginx编译参数：

./configure --prefix=/alidata/server/nginx 




关闭防火墙：
sudo systemctl stop firewalld
