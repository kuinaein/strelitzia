@echo off
rem WinRMのセットアップ
powershell -Command Set-NetConnectionProfile -NetworkCategory Private
rem winrm quickconfig -q

rem Ansibleのセットアップ
powershell -Command Invoke-WebRequest -Uri https://raw.githubusercontent.com/ansible/ansible/devel/examples/scripts/ConfigureRemotingForAnsible.ps1 -OutFile ConfigureRemotingForAnsible.ps1
powershell -ExecutionPolicy RemoteSigned .\ConfigureRemotingForAnsible.ps1
del ConfigureRemotingForAnsible.ps1
