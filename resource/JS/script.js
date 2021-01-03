function Init(){
    //is login
    if(window.localStorage.getItem("token") == null){
        window.location.href = "login.html";
        return;
    }
    //init avatar
    if(window.localStorage.getItem("avatarId") == null){
        window.localStorage.setItem("avatarId", Math.floor(Math.random()*10));
    }
    document.getElementById("avatar").innerHTML = "<img class=\"avatar\" src=\"resource/img/avatars/"+
        window.localStorage.getItem("avatarId") + ".png\">"
}

function searchClass(){

    data = document.getElementById("class_search").value;
    if(data.length == 0){
        alert("请输入查询关键词");
        return;
    }
    let http = "        <div class = \"container anchor pb-5\" id = \"classes\">\n" +
        "            <h1 class = \"text-container\">查询结果</h1>\n" +
        "            <div class = \"container d-block\">";

    let rawClass = "                <div class = \"class-container\">\n" +
        "                    <a class = \"class-link\" href = \"./comment.html\" target=\"_blank\" onclick=\"setSelected(@cid@)\">\n" +
        "                        <div class = \"class\">\n" +
        "                            <div class = \"class-head d-block\">\n" +
        "                                <h3 class = \"class-title\"> 课程：@courseName@</br>授课教师：@teacher@</h3>\n" +
        "                            </div>\n" +
        "                            <div class = \"class-body\">\n" +
        "                                <p class = \"classes-text g-comment color-black\">\n" +
        "                                    平均评分：@score@ 分\n" +
        "                                </p>\n" +
        "                            </div>\n" +
        "                        </div>\n" +
        "                    </a>\n" +
        "                </div>";

    let xmlhttp=new XMLHttpRequest();
    let url = "https://api.loganren.xyz/course/v1.0/course/search?keyword=" + data;


    xmlhttp.onreadystatechange = function (){

        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            // Response Correct
            let jsonobj = JSON.parse(xmlhttp.responseText);
            jsonobj = jsonobj.data;
            if(jsonobj.length === 0){
                //No data
                alert("未找到相关课程");
                document.getElementById("classes-container").innerHTML = "";
                return;
            }

            //构造html代码
            for (let i = 0;i<jsonobj.length;i++){
                obj = jsonobj[i];
                http += rawClass.replace("@courseName@", obj.coursename)
                    .replace("@teacher@", obj.teachername)
                    .replace("@score@", obj.score)
                    .replace("@cid@", obj.cid);
                storeScore(obj.cid, obj.score);
            }
            http += "</div>";
            // 修改页面代码
            document.getElementById("classes-container").innerHTML = http;

            //移动页面
            window.location.hash = "#classes-container"
            history.pushState(null, null ,"mainpage.html");
        }
    }
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function setSelected(cid){
    window.localStorage.setItem("curr_cid", cid);
}

function storeScore(cid, score){
    window.localStorage.setItem(cid, score);
}

function searchComment(){
    let classId = window.localStorage.getItem("curr_cid");
    let url = "https://api.loganren.xyz/course/v1.0/comment/"
    if(classId == null){
        alert("curr cid no found.");
        return;
    }
    url += classId.toString();

    let http = "        <div class = \"container anchor pb-5\">\n" +
        "            <h1 class = \"text-container\">评论</h1>\n" +
        "            <div class = container style=\"margin-top: 40px\">";

    let rawComment = "                <div class = \"board-holder comment\">\n" +
        "                    <div class = \"board-head bg-dimmed\">\n" +
        "                        <div class = d-flex style=\"width: 100%\">\n" +
        "                            <img class= \"comment-avatar\"src=\"resource/img/avatars/@number@.png\">\n" +
        "                            <div class = \"d-block right\" style=\"margin-top: 10px\">\n" +
        "                                <div class = \"board-head-context\">@time@</div>\n" +
        "                            </div>\n" +
        "                        </div>\n" +
        "                    </div>\n" +
        "                    <div class = \"board-text\">@content@</div>\n" +
        "                </div>"
    let xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange = function (){

        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            // Response Correct
            let jsonobj = JSON.parse(xmlhttp.responseText);
            jsonobj = jsonobj.data;
            if(jsonobj.length === 0){
                //No data
                //alert("未找到相关课程");
                document.getElementById("comment-container").innerHTML = "";
                return;
            }

            //构造html代码
            let curr = 0;
            for (let i = 0;i<jsonobj.length;i++){
                obj = jsonobj[i];
                curr +=Math.floor(Math.random()*10);
                curr%=10;
                http += rawComment.replace("@time@", obj.create_time)
                    .replace("@content@", obj.comment)
                    .replace("@number@", curr);
            }
            http += "            </div>\n" +
                "        </div>";
            // 修改页面代码
            document.getElementById("comment-container").innerHTML = http;

            //移动页面
            window.location.hash = "#comment-container"
            history.pushState(null, null ,"comment.html");
        }
    }
    xmlhttp.open("GET", url, true);
    xmlhttp.send();


}

function clearLocal(){
    window.localStorage.clear();
}

function postComment() {
    if(document.getElementById("content").value.length === 0){
        alert("请先输入评论。");
        return;
    }else if(document.getElementById("content").value.length > 200){
        alert("评论长度过长。")
        return;
    }
    var commentData = {
        "username": window.localStorage.getItem("username"),
        "cid": window.localStorage.getItem("curr_cid"),
        "comment":document.getElementById("content").value
    }
    $.ajax({
        url: "https://api.loganren.xyz/course/v1.0/comment",
        type: "post", //提交方式
        data: commentData,
        dataType: "JSON", //规定请求成功后返回的数据
        beforeSend:function (request){
            request.setRequestHeader("Authorization","Bearer " + window.localStorage.getItem("token"));
        },
        success: function() {
            alert("评论成功");
            searchComment();
            document.getElementById("content").value = "";
        },
        error: function() {
            clearLocal();
            alert("评论失败,登录凭证已过期，确认以重新登录。");
            window.location.href = "login.html";
        }
    });
    postScore();
}
function postScore(){
    var commentData = {
        "username": window.localStorage.getItem("username"),
        "cid": window.localStorage.getItem("curr_cid"),
        "score":(parseInt(document.getElementById("score").innerHTML[5]) + parseInt(document.getElementById("score").innerHTML[20])/10)*10
    }
    $.ajax({
        url: "https://api.loganren.xyz/course/v1.0/score",
        type: "post", //提交方式
        data: commentData,
        dataType: "JSON", //规定请求成功后返回的数据
        beforeSend:function (request){
            request.setRequestHeader("Authorization","Bearer " + window.localStorage.getItem("token"));
        },
    })
}