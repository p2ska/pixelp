var prefix = "pixelp",
	root = "/" + prefix + "/",
	path = $("#" + prefix + "_content").data("path"),
	history = window.History,
	state = history.getState(),
	lang;

history.Adapter.bind(window, "statechange", function() {
	state = history.getState();
	path = state.data["path"];
	navigate(path);
});

$("." + prefix + "_album").click(function() {
	var switch_path = $(this).data("path");

	if (path != switch_path) {
		navigate(switch_path);
		history.pushState({ path: switch_path }, false, root + switch_path);
		path = switch_path;
	}
});

function navigate(to) {
	$("#" + prefix + "_path").html("path: " + to);
	$("#" + prefix + "_content").load(root + prefix + ".php?path=" + to);
}

$(document).ready(function() {
	navigate(path);
});

(function($) {
    $.fn.pixelp = function() {
        var module		= "pixelp.php",
            debug       = true,
            settings    = [];

		// tagi järgi otsing

		$(document).on("click", prefix + "_tag", function() {
			alert($(this).data("tag"));
		});

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
