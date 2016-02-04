title: OAuth2.0
speaker: Janey He
url: https://github.com/ksky521/nodePPT
transition: cards
files: /css/ppt.css

[slide]

# OAuth2.0
## 演讲者：Janey He

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## OAuth2.0

* 什么是OAuth2.0
* 应用场景
* 流程
* 客户端授权模式
* 示例

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 什么是OAuth2.0

* OAuth(开放授权)是一个开放标准
* 无需将用户名和密码提供给第三方应用
* 允许用户让第三方应用访问该用户在某一网站上存储的私密的资源

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 应用场景

* 微信
* 新浪微博
* Facebook
* Twitter

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

##　流程

<img src="../images/oauth/abstract_protocol_flow.png" width="60%">
+ (A)用户打开客户端以后，客户端要求用户给予授权。
+ (B)用户同意给予客户端授权。
+ (C)客户端使用上一步获得的授权，向认证服务器申请令牌。
+ (D)认证服务器对客户端进行认证以后，确认无误，同意发放令牌。
+ (E)客户端使用令牌，向资源服务器申请获取资源。
+ (F)资源服务器确认令牌无误，同意向客户端开放资源。

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 客户端的授权模式

+ 授权码模式(authorization code) {:&.moveIn}
    * 是功能最完整、流程最严密的授权模式
    * 特点: 通过客户端的后台服务器，与"服务提供商"的认证服务器进行互动。
    * 使用(微信): http://mp.weixin.qq.com/wiki/4/9ac2e7b1f1d22e9e57260f6553822520.html
+ 简化模式(implicit)
    * 不通过第三方应用程序的服务器，直接在浏览器中向认证服务器申请令牌，跳过了"authorization code"这个步骤
    * 使用(百度): http://developer.baidu.com/wiki/index.php?title=docs/oauth/implicit
+ 密码模式(resource owner password credentials)
    * 用户向客户端提供自己的用户名和密码。客户端使用这些信息，向"服务商提供商"索要授权。
    * 用户必须把自己的密码给客户端，但是客户端不得储存密码
    * 通常用在用户对客户端高度信任的情况下
    * 认证服务器只有在其他授权模式无法执行的情况下，才能考虑使用这种模式。
+ 客户端模式(client credentials)
    * 指客户端以自己的名义，而不是以用户的名义，向"服务提供商"进行认证
    * 在这种模式中，用户直接向客户端注册，客户端以自己的名义要求"服务提供商"提供服务，其实不存在授权问题。
    * 使用(百度): http://developer.baidu.com/wiki/index.php?title=docs/oauth/client

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 授权码模式(authorization code)

<img src="../images/oauth/authorization_code_flow.png" width="40%">
+ (A)用户访问客户端，后者将前者导向认证服务器。
+ (B)用户选择是否给予客户端授权。
+ (C)假设用户给予授权，认证服务器将用户导向客户端事先指定的"redirection URI"，同时附上一个授权码。
+ (D)客户端收到授权码，附上早先的"redirection URI"，向认证服务器申请令牌。这一步是在客户端的后台的服务器上完成的，对用户不可见。
+ (E)认证服务器核对了授权码和重定向URI，确认无误后，向客户端发送访问令牌(access token)和更新令牌(refresh token)。

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 授权码模式(authorization code)示例

+ A步骤中: 用户访问客户端，后者将前者导向认证服务器。
    * 如: 
    ```
    https://open.weixin.qq.com/connect/oauth2/authorize?
    appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE
    &state=STATE#wechat_redirect
    ```
        - response_type：表示授权类型，必选项，此处的值固定为"code"
        - client_id：表示客户端的ID，必选项(微信中为appid)
        - redirect_uri：表示重定向URI，可选项
        - scope：表示申请的权限范围，可选项
        - state：表示客户端的当前状态，可以指定任意值，认证服务器会原封不动地返回这个值。
+ D步骤中，客户端向认证服务器申请令牌的HTTP请求
    * 如: 
    ```
    https://api.weixin.qq.com/sns/oauth2/access_token?
    appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
    ```
        - grant_type：表示使用的授权模式，必选项，此处的值固定为"authorization_code"。
        - code：表示上一步获得的授权码，必选项。
        - redirect_uri：表示重定向URI，必选项，且必须与A步骤中的该参数值保持一致。
        - client_id：表示客户端ID，必选项(微信中为appid)

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 简化模式(implicit)

<img src="../images/oauth/implicit_grant_flow_origin.png" width="40%">
+ (A)客户端将用户导向认证服务器。
+ (B)用户决定是否给于客户端授权。
+ (C)假设用户给予授权，认证服务器将用户导向客户端指定的"重定向URI"，并在URI的Hash部分包含了访问令牌。
+ (D)浏览器向资源服务器发出请求，其中不包括上一步收到的Hash值。
+ (E)资源服务器返回一个网页，其中包含的代码可以获取Hash值中的令牌。
+ (F)浏览器执行上一步获得的脚本，提取出令牌。
+ (G)浏览器将令牌发给客户端。

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 简化模式(implicit)示例

+ A步骤中: 客户端将用户导向认证服务器。
    * 如: 
    ```
    GET /authorize?response_type=token&client_id=s6BhdRkqt3&state=xyz
        &redirect_uri=https%3A%2F%2Fclient%2Eexample%2Ecom%2Fcb HTTP/1.1
    Host: server.example.com
    ```
        - response_type：表示授权类型，此处的值固定为"token"，必选项。
        - client_id：表示客户端的ID，必选项。
        - redirect_uri：表示重定向的URI，可选项。
        - scope：表示权限范围，可选项。
        - state：表示客户端的当前状态，可以指定任意值，认证服务器会原封不动地返回这个值。
+ C步骤中: 认证服务器将用户导向客户端指定的"redirection URI"，并在URI的Hash部分包含了访问令牌。
    * 如: 
    ```
    HTTP/1.1 302 Found
    Location: http://example.com/cb#access_token=2YotnFZFEjr1zCsicMWpAA
               &state=xyz&token_type=example&expires_in=3600
    ```
        - access_token：表示访问令牌，必选项。
        - token_type：表示令牌类型，该值大小写不敏感，必选项。
        - expires_in：表示过期时间，单位为秒。如果省略该参数，必须其他方式设置过期时间。
        - scope：表示权限范围，如果与客户端申请的范围一致，此项可省略。
        - state：如果客户端的请求中包含这个参数，认证服务器的回应也必须一模一样包含这个参数。

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 密码模式(Resource Owner Password Credentials)

<img src="../images/oauth/resource_owner_password_credentials_flow_origin.png" width="60%">
+ (A)用户向客户端提供用户名和密码。
+ (B)客户端将用户名和密码发给认证服务器，向后者请求令牌。
+ (C)认证服务器确认无误后，向客户端提供访问令牌。

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 密码模式(Resource Owner Password Credentials)示例

+ B步骤中:
    * 如: 
    ```
    POST
    https://openapi.baidu.com/oauth/2.0/token?
    grant_type=client_credentials&
    client_id=Va5yQRHlA4Fq4eR3LT0vuXV4&
    client_secret= 0rDSjzQ20XUj5itV7WRtznPQSzr5pVw2&
    ```
        - grant_type：表示授权类型，此处的值固定为"password"，必选项。
        - username：表示用户名，必选项。
        - password：表示用户的密码，必选项。
        - scope：表示权限范围，可选项。
+ C步骤中:
    * 如:
    ```
    HTTP/1.1 200 OK
    Content-Type: application/json;charset=UTF-8
    Cache-Control: no-store
    Pragma: no-cache

    {
        "access_token":"2YotnFZFEjr1zCsicMWpAA",
        "token_type":"example",
        "expires_in":3600,
        "refresh_token":"tGzv3JOkF0XG5Qx2TlKWIA",
        "example_parameter":"example_value"
    }
    ```

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 客户端模式(Client Credentials)

<img src="../images/oauth/client_credentials_flow_orign.jpg" width="60%">
+ (A)客户端向认证服务器进行身份认证，并要求一个访问令牌。
+ (B)认证服务器确认无误后，向客户端提供访问令牌。

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 客户端模式(Client Credentials)示例

+ A步骤中:
    * 如:
    ```
    POST /token HTTP/1.1
    Host: server.example.com
    Authorization: Basic czZCaGRSa3F0MzpnWDFmQmF0M2JW
    Content-Type: application/x-www-form-urlencoded

    grant_type=client_credentials
    ```
        - granttype：表示授权类型，此处的值固定为"clientcredentials"，必选项。
        - scope：表示权限范围，可选项。
+ B步骤中:
    * 如:
    ```
    HTTP/1.1 200 OK
    Content-Type: application/json;charset=UTF-8
    Cache-Control: no-store
    Pragma: no-cache

    {
        "access_token":"2YotnFZFEjr1zCsicMWpAA",
        "token_type":"example",
        "expires_in":3600,
        "example_parameter":"example_value"
    }

    ```

[slide style="font-size: 16px;overflow: auto;word-break: break-all;"]

## 更新access_token

<img src="../images/oauth/refreshing_an_expired_access_token_origin.jpg" width="30%">
    + 如:

    ```
    POST /token HTTP/1.1
    Host: server.example.com
    Authorization: Basic czZCaGRSa3F0MzpnWDFmQmF0M2JW
    Content-Type: application/x-www-form-urlencoded

    grant_type=refresh_token&refresh_token=tGzv3JOkF0XG5Qx2TlKWIA
    ```
        * granttype：表示使用的授权模式，此处的值固定为"refreshtoken"，必选项。
        * refresh_token：表示早前收到的更新令牌，必选项。
        * scope：表示申请的授权范围，不可以超出上一次申请的范围，如果省略该参数，则表示与上一次一致。


[slide]

## 参考资料

http://oauth.net/2/
https://www.rfc-editor.org/rfc/pdfrfc/rfc6749.txt.pdf
http://developer.baidu.com/wiki/index.php?title=docs/oauth/implicit

[slide]

# Thanks