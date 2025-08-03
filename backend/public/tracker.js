(function () {
    const TRACKER_URL = 'http://localhost:8080/api/track';

    function uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            let r = Math.random() * 16 | 0,
                v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    function getVisitorId() {
        let id = localStorage.getItem('tracker_visitor_id');
        if (!id) {
            id = uuidv4();
            localStorage.setItem('tracker_visitor_id', id);
        }
        return id;
    }

    function track() {
        const payload = {
            url: window.location.href,
            userAgent: navigator.userAgent,
            visitorId: getVisitorId(),
        };

        fetch(TRACKER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload)
        }).catch((err) => {
            console.error('Tracker failed:', err);
        });
    }

    window.addEventListener('load', track);
})();