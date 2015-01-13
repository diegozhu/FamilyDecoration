@echo off 
setlocal enabledelayedexpansion
title backup

cd /d C:\Program Files (x86)\Zend\Apache2\htdocs\fd\bin

echo 开始备份

rem 创建文件夹
for /f "tokens=1 delims=/ " %%j in ("%date%") do set d1=%%j
for /f "tokens=2 delims=/ " %%j in ("%date%") do set d2=%%j
for /f "tokens=3 delims=/ " %%j in ("%date%") do set d3=%%j
for /f "tokens=1 delims=: " %%j in ("%time%") do set t1=%%j
for /f "tokens=2 delims=: " %%j in ("%time%") do set t2=%%j
for /f "tokens=3 delims=:. " %%j in ("%time%") do set t3=%%j
set dirName=%d1%-%d2%-%d3%-%t1%-%t2%-%t3%
cd ../backups/

echo 正在备份数据库
rem 备份数据库 和 上次升级脚本
mysqldump -hlocalhost -uroot  familydecoration  > database.sql
svn add database.sql > nul 2>&1
echo 创建备份文件夹：%dirName%
mkdir %dirName%

cd ../
move jobs/update.bat backups/%dirName% > nul 2>&1

rem 更新svn
echo 正在更新
svn update -q

rem 执行本次升级脚本
jobs/update.bat >nul 2>&1

rem 获取需要备份的pdf 列表
cd backups/%dirName%
echo 正在获取备份文件列表
..\..\bin\cur -s "http://127.0.0.1:82/fd/libs/budget.php?action=listIds" > budgetIds.txt
rem svn add budgetIds.txt

rem 创建pdf备份文件夹
mkdir backup-pdf
mkdir backup-xls

rem 遍历curl 获取pdf
for /f %%a in ('type budgetIds.txt') do (
	set line=%%a
	set url1="http://127.0.0.1:82/fd/fpdf/index2.php?budgetId=!line:~0,25!"
	set url2="http://127.0.0.1:82/fd/phpexcel/index.php?budgetId=!line:~0,25!"
	set fileName1="!line:~26!.pdf"
	set fileName2="!line:~26!.xls"
	echo 正在备份文件!fileName1!
	..\..\bin\cur -s !url1! >  !fileName1!
	echo 正在备份文件!fileName2!
	..\..\bin\cur -s !url2! >  !fileName2!
)

echo 所有文件备份完成
rem 移动到pdf 备份文件夹
move *.pdf backup-pdf/ > nul 2>&1
move *.xls backup-xls/ > nul 2>&1

echo 正在上传数据库
cd ../
svn commit ./database.sql -m backup -q

rem 回到root目录
cd ../bin/

echo 备份完成,按任意键退出
pause