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
        "                            <img src=\"resource/img/avatars/@number@.png\" style=\"width: 60px; border-radius: 20px\">\n" +
        "                            <div class = \"d-block right\" style=\"margin-top: 10px\">\n" +
        "                                <div class = \"board-head-context\">@time@</div>\n" +
        "                                <div class = \"board-head-context \">评分：@score@</div>\n" +
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
            for (let i = 0;i<jsonobj.length;i++){
                obj = jsonobj[i];
                http += rawComment.replace("@time@", obj.create_time)
                    .replace("@score@", window.localStorage.getItem(window.localStorage.getItem("curr_cid")))
                    .replace("@content@", obj.comment)
                    .replace("@number@", Math.floor(Math.random()*10));
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

function sendMassage() {
    $.ajax({
        url: "TestJsonServlet", //提交的路径
        type: "post", //提交方式
        data: 2,
        dataType: "JSON", //规定请求成功后返回的数据
        success: function(data) {
            if (isStrEmpty(data.error)) {
                alert(data.success);
            } else {
                alert(data.error);
            }
        },
        error: function() {
            alert("error!");
        }
    });

}
