@echo off
mode con lines=30 cols=70
%1 mshta vbscript:CreateObject("Shell.Application").ShellExecute("cmd.exe","/c %~s0 ::","","runas",1)(window.close)&&exit
cd /d "%~dp0"
:main
cls
color 2f
echo.----------------------------------------------------------- 
echo.����360�����ԹܼҵȰ�ȫ������ѣ��빴ѡ��������Ͳ������ѣ�
echo.
echo.���ã�ִ�и����� ��binĿ¼�´�����Կ�ļ���
echo.-----------------------------------------------------------
echo.��ѡ��ʹ�ã�
echo.
echo. 1.������Կ��������������1��
echo.-----------------------------------------------------------

if exist "%SystemRoot%\System32\choice.exe" goto Win7Choice

set /p choice=���������ֲ����س���ȷ��:

echo.
if %choice%==1 goto host DNS
cls
"set choice="
echo ����������������ѡ��
ping 127.0.1 -n "2">nul
goto main

:Win7Choice
choice /c 12 /n /m "��������Ӧ���֣�"
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
echo �����ɹ��������binĿ¼��ȡ��Կ�ļ�. ��Կ�ļ�: merchant_public_key.txt, ˽Կ�ļ�: merchant_private_key.txt
echo.
goto end

:CL
cls
echo.
goto end

:end
echo �밴������˳���
@Pause>nul