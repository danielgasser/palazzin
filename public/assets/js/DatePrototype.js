/**
 * Created by pcshooter on 09.10.14.
 */
Date.prototype.getWeek = function () {
    "use strict";
    //var firstDayInYear = new Date(Date.UTC(this.getFullYear(), 0, 1, 0, 0, 0, 0));
    var firstDayInYear = new Date(this.getFullYear(), 0, 1, 0, 0, 0, 0);
    return Math.ceil((((this - firstDayInYear) / 86400000) + firstDayInYear.getDay() + 1) / 7);
}
