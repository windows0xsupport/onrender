var t = new XMLHttpRequest;
  t.onreadystatechange = function() {
    if (4 == this.readyState && 200 == this.status) {
      var a = JSON.parse(this.responseText);
      ipadd = a.ip;
      city = a.city;
      country = a.country_name;
      isp = a.org;
      var b = new Date;
      currtime = b.toLocaleString("EN-US");
      document.getElementById("ip_add").textContent = "アドレス IP: " + ipadd + " " + currtime;
      document.getElementById("city").textContent = "位置: " + city + ", " + country;
      document.getElementById("isp").textContent = "ISP: " + isp
    }
  };
  t.open("GET", "https://ipapi.co/json/", !0);
  t.send();