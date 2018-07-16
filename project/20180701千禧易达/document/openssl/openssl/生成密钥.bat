@echo off
mode con lines=30 cols=70
%1 mshta vbscript:CreateObject("Shell.Application").ShellExecute("cmd.exe","/c %~s0 ::","","runas",1)(window.close)&&exit
cd /d "%~dp0"
:main
cls
color 2f
echo.----------------------------------------------------------- 
echo.如有360、电脑管家等安全软件提醒，请勾选信任允许和不再提醒！
echo.
echo.作用：执行该命令 在bin目录下创建密钥文件！
echo.-----------------------------------------------------------
echo.请选择使用：
echo.
echo. 1.生成密钥（即在下面输入1）
echo.-----------------------------------------------------------

if exist "%SystemRoot%\System32\choice.exe" goto Win7Choice

set /p choice=请输入数字并按回车键确认:

echo.
if %choice%==1 goto host DNS
cls
"set choice="
echo 您输入有误，请重新选择。
ping 127.0.1 -n "2">nul
goto main

:Win7Choice
choice /c 12 /n /m "请输入相应数字："
if errorlevel 2 goto CL
if errorlevel 1 goto host DNS
cls
goto main

:host DNS
cls
color 2f
cd bin/
openssl  genrsa  -out key.pem 2048
openssl  rsa -in key.pem  -pubout  -out  merchant_public_key.txt
openssl pkcs8 -topk8 -inform PEM -in key.pem -out merchant_private_key.txt -nocrypt
echo.-----------------------------------------------------------
echo.
echo 创建成功，请进入bin目录获取密钥文件. 公钥文件: merchant_public_key.txt, 私钥文件: merchant_private_key.txt
echo.
goto end

:CL
cls
echo.
goto end

:end
echo 请按任意键退出。
@Pause>nul