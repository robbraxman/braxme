setInterval(function () {
    postMessage(JSON.stringify({
        message: "tiktok",
        result: new Date().getTime()
    }));
}, 500);