let BrowserNotification = {
    notification: null,
    options: {
        title: 'Neue Benachrichtigung',
        body: 'Neue Benachrichtigung auf palazzin.ch',
        icon: 'https://palazzin.ch/public/img/logo_notification.jpg',
        link:  'https://palazzin.ch'
    },
    notifyMe: function(title, text, link) {
        let that = BrowserNotification;
        if (!("Notification" in window)) {
            return false;
        }
        if (title !== undefined) {
            that.options.title = title;
        }
        if (text !== undefined) {
            that.options.body = text;
        }
        if (link !== undefined) {
            that.options.link += link;
        }
        if (Notification.permission === "granted") {
            that.notification = new Notification(that.options.title, that.options);
        }

        else if (Notification.permission !== 'denied') {
            Notification.requestPermission(function (permission) {
                if (permission === "granted") {
                    that.notification = new Notification(that.options.title, that.options);
                }
            });
        }
        that.notification.onclick = function () {
            window.location.href = that.options.link;
        }
    }
};
