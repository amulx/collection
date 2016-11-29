var popWin = {
	scrolling: "auto",
	"int": function() {
		this.mouseClose(),
		this.closeMask()
	},
	mouseClose: function() {
		$("#popWinClose").on("mouseenter",
		function() {
			$(this).css("background-image", "url(../img/rightfloat/closehdtipper.png)")
		}),
		$("#popWinClose").on("mouseleave",
		function() {
			$(this).css("background-image", "url(../img/rightfloat/closehdtipper.png)")
		})
	},
	closeMask: function() {
		$("#popWinClose").on("click",
		function() {
			$("#mask,#maskTop").fadeOut(function() {
				$(this).remove()
			})
		})
	}
};

function b() {
	h = $(window).height(),
	t = $(document).scrollTop(),
	t > h ? $("#moquu_wshare").show() : $("#moquu_wshare").hide()
}
$(document).ready(function() {
	b(),
	$("#moquu_wshare").click(function() {
		//$(document).scrollTop(0);
        $("html, body").animate({
            scrollTop: 0
        }, "slow")
	})
}),
$(window).scroll(function() {
	b()
});