var ptable_url = "pixelp.php";

(function($) {
    $.fn.pixelp = function() {
        var prefix      = "#pixelp_",
            debug       = false,
            settings    = [];

        // korralikum logi formaatimine

        function clog(where, what, block) {
            if (!debug)
                return false;

            var date = new Date();
            var hrs = date.getHours(), min = date.getMinutes(), sec = date.getSeconds();
            var time = "[" + hrs + ":" + (min < 10 ? "0" + min : min) + ":" + (sec < 10 ? "0" + sec : sec) + "] ";
            var sep = "-------------------------";

            if (where === "-") {
                console.log(sep);
            }
            else if (log_block && (what === ";" || block === ";")) {
                log_block = false;

                console.log("\t" + where);
                console.log("}");
            }
            else if (block) {
                log_block = true;

                console.log(time + fixed_len(block).toUpperCase() + ": " + where + " {");
                console.log("\t" + what);
            }
            else {
                if (log_block)
                    console.log("\t" + where);
                else
                    console.log(time + fixed_len(where).toUpperCase() + ": " + what);
            }
        }

        // clog'i kirje joondamise jaoks

        function fixed_len(str, count) {
            var l = str.length;

            if (!count)
                count = 10;

            if (l > count)
                return str.substr(0, count);
            else
                return str + new Array(count + 1 - l).join(" ");
        }
	}

	$().pixelp();
}(jQuery));
