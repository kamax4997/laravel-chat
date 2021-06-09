<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <style>
            .main-body {
                max-width: 700px;
                margin: 0 auto;
            }
            .header-part {
                background-color: #fff;
                border-radius: 40px;
                margin-top: 40px;
            }
            .header-part:last-child {
                padding: 20px;
            }
            .header-title {
                background-color: #2f76b0;
                border-top-left-radius: 40px;
                border-top-right-radius: 40px;
                justify-content: center;
                display: flex;
                padding: 30px 0;
            }
            .logo-icon {
                width: 50px;
                height: 50px;
                margin-top: 15px;
            }
            .title-part {
                color: #fff;
                margin-left: 20px;
                font-size: 26px;
            }
            .main-title {
                font-size: 50px;
                display: block;
            }
            .body-title,
            .bottom-title {
                color: black;
                font-size: 50px;
                display: block;
                margin: 15px;
            }
            .envelop-img {
                width: 200px;
                margin-top: 20px;
            }
            .header-body {
                text-align: center;
            }
            .header-div {
                text-align: left;
                font-size: 20px;
                padding: 15px;
            }
            .header-div span {
                display: block;
            }
            .header-div p {
                font-weight: 500;
            }
            .header-div div {
                margin-top: 5px;
            }
            .username-span {
                display: inline !important;
                color: #2f76b0;
            }
            .login-title {
                display: inline !important;
                font-weight: 500;
            }
            .member-detail {
                margin-top: 30px !important;
                color: #2f76b0;
            }
            .acive-btn {
                background-color: #2f76b0;
                color: #fff;
                border-top-left-radius: 30px;
                border-top-right-radius: 30px;
                border-bottom-right-radius: 30px;
                border-bottom-left-radius: 30px;
                padding: 10px 50px;
                text-decoration: none;
                font-size: 24px;
                margin: 30px 0;
                display: inline-block;
            }
            body {
                background-color: #CFE8FD;
            }
            .bottom-title {
                text-decoration: underline;
                text-align: center;
            }
            .bottom-body {
                padding: 10px 20px;
            }
            .sub-title {
                display: flex;
            }
            .sub-title span {
                color: #2f76b0;
                font-size: 24px;
                display: inline-block;
                margin-left: 15px;
                font-weight: 600;
                margin-top: 10px;
            }
            .sub-text {
                padding-left: 35px;
            }
            .sub-text ul {
                font-size: 18px;
                font-weight: 500;
            }
            .red-text {
                margin-top: 30px;
                color: red;
                font-size: 18px;
            }
            .bottom-text {
                font-size: 24px;
                font-weight: 500;
                display: block;
            }
            .bottom-url {
                color: blue;
                font-size: 22px;
                font-weight: 500;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="main-body">
            <div class="header-part">
                <div class="header-title">
                    <img src="{{asset('assets/logo.png')}}" class="logo-icon" />
                    <div class="title-part">
                        <span class="main-title">Chat Horizon</span>
                        Connectiong the world
                    </div>
                </div>
                <div class="header-body">
                    <span class="body-title">Your New Account</span>
                    <img src="{{asset('assets/envelop.png')}}" class="envelop-img" />
                    <div class="header-div">
                        <p>Hi <span class="username-span">{username}</span></p>
                        <span>Thank you for taking the time to register an account at chat horizon. In order</span>
                        <span>to use your new account you must verify your email address. If you do not</span>
                        <span>verify your email address within 3 days your account will be automatically</span>
                        <span>deleted from the system.</span>
                        <div class="member-detail">Your membership details are:</div>
                        <div><span class="login-title">Email:</span> {usermail}</div>
                        <div><span class="login-title">User Name:</span> {username}</div>
                        <div><span class="login-title">Activation code:</span> {activecode}</div>
                    </div>
                    <a class="acive-btn" href="http://chathorizon.net/activate-account" target="_blank">Activate</a>
                </div>
            </div>
            <div class="header-part">
                <span class="bottom-title">Account Benefits</span>
                <div class="bottom-body">
                    <div class="sub-title">
                        <img src="{{asset('assets/avatar.png')}}" class="subtitle-img" />
                        <span>User Names</span>
                    </div>
                    <div class="sub-text">
                        <ul>
                            <li>Create multiple user names under the one account when you have been chatting for 24 hrours</li>
                            <li>Own your user name, this means other chatters can not chat with your user name</li>
                            <li>Your users avatar is saved on the system</li>
                        </ul>
                    </div>
                    <div class="sub-title">
                        <img src="{{asset('assets/chat.png')}}" class="subtitle-img" />
                        <span>Chat Rooms</span>
                    </div>
                    <div class="sub-text">
                        <ul>
                            <li>Register up 3 chat rooms which can be set to stay open even if no chatters are in the room</li>
                            <li>Appint trusted users to host or manager level to help manage your room.</li>
                            <li>Option to remember your interface when joining chat rooms.</li>
                        </ul>
                    </div>
                    <div class="sub-title">
                        <img src="{{asset('assets/profile.png')}}" class="subtitle-img" />
                        <span>More Communication</span>
                    </div>
                    <div class="sub-text">
                        <ul>
                            <li>Communicate with offline users by using our discussion board system.</li>
                            <li>Broadcast youtube videos in real time to users in the same chat room.</li>
                            <li>Commnunicate privately with online and offline users via our private messaing system.</li>
                        </ul>
                    </div>
                    <p class="red-text">If you did not register an account with chat horizon please delete this email.</p>
                    <hr/>
                    <span class="bottom-text">Chat Horizon Account Team</span>
                    <a class="bottom-url" href="http://chathorizon.net" target="_blank">http://chathorizon.net</a>
                </div>
            </div>
        </div>
    </body>
</html>