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

function searchComment(){
    let classId = window.localStorage.getItem("curr_cid", cid);
    if(classId == null){
        alert("curr cid no found.");
        return;
    }

}