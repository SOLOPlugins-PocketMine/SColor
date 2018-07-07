# SColor
서버 내에서 색챗을 제어하는 플러그인입니다.
색챗을 아예 금지하거나 허용할 수 있으며, 명령어를 통해 사용가능한 색의 목록을 확인할 수도 있습니다.
`&`를 `§`처럼 사용하는 기능도 제공합니다. 예) `&a` = `§a`
![](https://i.imgur.com/fZp6R7r.png)

<br>

## 체인지로그
* **0.0.2 (2017.08.16)** : 첫 릴리즈
* **1.0.0 (2018.06.24)** : PocketMine-MP API 3.0.0 ~ 4.0.0 호환 패치

<br>

## 명령어
|명령어|퍼미션|기본값|설명|
|-|-|-|-|
|`/색상표`|scolor.command.color|ALL|사용 가능한 색 목록을 확인할 수 있습니다.|

<br>

## 색상 코드 퍼미션
|색상 이름|색상 코드|퍼미션|
|-|-|-|
|굵기|l|scolor.style.bold|
|기울이기|o|scolor.style.italic|
|무작위|k|scolor.style.obfuscated|
|리셋|r|scolor.style.reset|
|검정|0|scolor.color.black|
|짙은 파랑|1|scolor.color.darkblue|
|짙은 초록|2|scolor.color.darkgreen|
|짙은 하늘|3|scolor.color.darkaqua|
|짙은 빨강|4|scolor.color.darkred|
|보라|5|scolor.color.purple|
|주황|6|scolor.color.gold|
|회색|7|scolor.color.gray|
|진한 회색|8|scolor.color.darkgray|
|파랑|9|scolor.color.blue|
|초록|a|scolor.color.green|
|하늘|b|scolor.color.aqua|
|빨강|c|scolor.color.red|
|분홍|d|scolor.color.lightpurple|
|노랑|e|scolor.color.yellow|
|하양|f|scolor.color.white|

<br>

## setting.yml 설정
플러그인을 넣고 서버를 켜면 플러그인 폴더 내에 **setting.yml** 파일이 생성됩니다. 이 파일을 수정하여 작동 방식을 세부적으로 설정할 수 있습니다.

|설정|설명|
|-|-|
|allow-color-on-chat|채팅창에서 색상 코드 사용을 허용 또는 비허용합니다.|
|allow-color-on-sign|표지판에서 색상 코드 사용을 허용 또는 비허용합니다.|
|allow-colors|사용 가능한 색 코드를 설정합니다.|
