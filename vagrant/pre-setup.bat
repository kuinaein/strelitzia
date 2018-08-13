rem @echo off

echo Setup WinRM...
powershell -Command Set-NetConnectionProfile -NetworkCategory Private
powershell -Command Enable-PSRemoting
rem winrm quickconfig -q

echo Setup Ansible...
powershell -Command Invoke-WebRequest -Uri https://raw.githubusercontent.com/ansible/ansible/devel/examples/scripts/ConfigureRemotingForAnsible.ps1 -OutFile ConfigureRemotingForAnsible.ps1
powershell -ExecutionPolicy RemoteSigned .\ConfigureRemotingForAnsible.ps1
del ConfigureRemotingForAnsible.ps1

echo Open User Accounts Settings...
netplwiz
