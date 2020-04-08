
function ajax_get_request(url, success, err) {
    var xhr = new XMLHttpRequest();
    xhr.open('get',url, true)
    xhr.send()
    xhr.onreadystatechange = function() {
        if (xhr.status == 200 && xhr.readyState == 4) {
            success(xhr)
        } else {
            err(xhr)
        }
    }
}

function ajax_post_request(url, param, success, err) {
    var xhr = new XMLHttpRequest();
    xhr.open('post',url, true)
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(param)
    xhr.onreadystatechange = function() {
        if (xhr.status == 200 && xhr.readyState == 4) {
            success(xhr)
        } else {
            err(xhr)
        }
    }
}