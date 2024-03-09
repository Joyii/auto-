@echo off
setlocal enabledelayedexpansion

REM 清空或创建输出文件
echo. > hebing_out.txt

REM 循环遍历当前目录下的所有php文件
for %%f in (*.php) do (
    echo %%f: >> hebing_out.txt
    echo ">> hebing_out.txt
    type "%%f" >> hebing_out.txt
    echo ">> hebing_out.txt
    echo.>> hebing_out.txt
)

echo 合并完成。
