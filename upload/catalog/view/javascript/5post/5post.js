if ($(window).width() <= 1024) {
    var width = ($(window).width() * 19) / 20;
} else {
    var width = ($(window).width() * 3) / 4;
}
if ($(window).height() < 1024) {
    var height = ($(window).height() * 14) / 20;
} else {
    var height = 900;
}


$(document).ready(function () {
    $('[name="shipping_method"]').removeAttr("checked");

    if (width < 1024) {
        $("#fivepost-modal .fivepost-modal-dialog").removeAttr("style");
    }
});

$(window).resize(function () {
    if ($(window).width() <= 1024) {
        var width = ($(window).width() * 19) / 20;
    } else {
        var width = ($(window).width() * 3) / 4;
    }
    if ($(window).height() < 1024) {
        var height = ($(window).height() * 14) / 20;
    } else {
        var height = 900;
    }
    if (width < 1024) { 
        $("#fivepost-modal .fivepost-modal-dialog").removeAttr("style");
    } else {
        $("#fivepost-modal .fivepost-modal-dialog").css("width", width + "px");
    }

    if($(window).width() <= 767) {
    	var fivepostMapH = $('#fivepost_map').parents('.fivepost-map-container').height();
		$("#fivepost_map").height(fivepostMapH);
		$('#fivepost-modal .fivepost-modal-dialog .fivepost-pvz-list').removeAttr("style");
		if($('.fivepost-pvz-list .tab-content').hasClass('open')) {
			$('.fivepost-pvz-list .tab-content').height(fivepostMapH);
		}
	} else {
		$("#fivepost_map").height(height);
		$('#fivepost-modal .fivepost-modal-dialog .fivepost-pvz-list').height(height);
		if($('.fivepost-pvz-list .tab-content').hasClass('open') || $('.fivepost-pvz-list .tab-content').height() === 0) {
			$('.fivepost-pvz-list .tab-content').removeAttr("style");
			$('.fivepost-pvz-list .tab-content').removeClass('open');
		}
	}
});

$(document).on("click", '[name="shipping_method"]', function () {
    shipping_method = $("input[name='shipping_method']:checked").val();

    if (typeof reloadAll == "function") {
        reloadAll();
    }

     if (shipping_method == "5post.5post") {
        if (document.getElementById("fivepost-hide-pvz")) {
            document.getElementById("fivepost-hide-pvz").style.display = "block";
        } else if (document.getElementById("fivepost-hide-pvz-simple")) {
            document.getElementById("fivepost-hide-pvz-simple").style.display =
                "block";
        }
    } else {
        if (document.getElementById("fivepost-hide-pvz")) {
            document.getElementById("fivepost-hide-pvz").style.display = "none";
        } else if (document.getElementById("fivepost-hide-pvz-simple")) {
            document.getElementById("fivepost-hide-pvz-simple").style.display =
                "none";
        }
    }
});

var fivepostModal = $('<div class="fivepost-modal fivepost-fade" id="fivepost-modal" tabindex="-1" role="dialog"></div>');


var fivepostModalContent =
    '<div class="fivepost-modal-dialog fivepost-modal-lg">' +
    '<div class="fivepost-modal-content">' +
    '<div class="fivepost-modal-header">' +
    '<button type="button" class="fivepost-close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">×</span></button>' +
    '<h4 class="fivepost-modal-title"></h4>' +
    "</div>" +
    '<div class="fivepost-modal-body">' +
    '<div class="fivepost-container-fluid">' +
    '<div class="fivepost-row fivepost-map-row">' +
    '<div class="fivepost-col-md-3 fivepost-pvz-list" id="fivepost-filter_content"></div>' +
    '<div class="fivepost-col-sm-12 fivepost-col-md-9 fivepost-map-container">' +
    '<div id="fivepost_map"></div>' +
    "</div></div></div></div></div></div>";

fivepostModal.append(fivepostModalContent);

if($(window).width() > 767) {
	fivepostModal.find('.fivepost-modal-dialog').width(width);
	fivepostModal.find('.fivepost-pvz-list').height(height);
	fivepostModal.find('#fivepost_map').height(height);
}

var fivepostMap;

$(document).on("click", "#fivepost-open-pvz-simple", function () {
    Fivepost.points();
});

$(document).on("click", "#fivepost-open-pvz", function () {
    Fivepost.points();
});

var Fivepost = {
    points: function () {
        $("#fivepost-open-pvz").attr("disabled","disabled");
		$("#fivepost-open-pvz").button('loading');
		
		$("#fivepost-open-pvz-simple").attr("disabled","disabled");
		$("#fivepost-open-pvz-simple").button('loading');
		
        $("#fivepost-modal").remove();

        $("body").append(fivepostModal);
        ymaps.ready(init);

        function init() {
            fivepostMap = new ymaps.Map(
                "fivepost_map",
                {
                    center: [55.76, 37.64],
                    zoom: 9,
					controls: ["zoomControl"],
                },
                {
                   
                }
            );
			
			fivepostMap.controls.remove('searchControl');			

            if ($(window).width() >= 767) {
                var fBallibContentHead =
                    '<div style="line-height: 170%;">';
            } else {
                var fBallibContentHead =
                    '<div style="line-height: 100%; width: 180px; margin: 10px;">';
            }

            var BalloonContentLayout = ymaps.templateLayoutFactory.createClass(
                fBallibContentHead +
                    '<b>Адрес пункта выдачи заказов:</b></br><h4 style="margin-top: 0px;">{{properties.address}}</h4>' +
                    '[if properties.workingHours.0.day]<b>Время работы:</b></br>' +
                    '<i class="fa fa-clock-o" aria-hidden="true"></i> {{properties.workingHours[0].day}}</br>[endif]' +
                    '[if properties.workingHours.1.day]<i class="fa fa-clock-o" aria-hidden="true"></i> {{properties.workingHours[1].day}}</br>[endif]' +
                    '[if properties.workingHours.2.day]<i class="fa fa-clock-o" aria-hidden="true"></i> {{properties.workingHours[2].day}}</br>[endif]' +
                    '[if properties.workingHours.3.day]<i class="fa fa-clock-o" aria-hidden="true"></i> {{properties.workingHours[3].day}}</br>[endif]' +
                    '[if properties.workingHours.4.day]<i class="fa fa-clock-o" aria-hidden="true"></i> {{properties.workingHours[4].day}}</br>[endif]' +
                    '[if properties.workingHours.5.day]<i class="fa fa-clock-o" aria-hidden="true"></i> {{properties.workingHours[5].day}}</br>[endif]' +
                    '[if properties.workingHours.6.day]<i class="fa fa-clock-o" aria-hidden="true"></i> {{properties.workingHours[6].day}}[endif]' +
					'[if properties.description]<p style="margin: 2px 0px 0px 0px;"><b>Описание:</b></p><h4 style="margin-top: 0px;">{{properties.description}}</h4>[endif]' +
					'<b>Способы оплаты:</b></br>' +
					'[if properties.cashAllowed == 1]Расчет наличными</br>[endif]' +
					'[if properties.cardAllowed == 1]Расчет картой</br>[endif]' +
					'<b class="fivepost-price-title">Стоимость: </b><i class="fivepost-price">{{properties.price}}</i>' +
                    '<button type="button" class="btn btn-outline-secondary btn-sm" dpi="{{properties.id}}" terms="{{properties.terms}}" price="{{properties.price}}" rt="{{properties.rate_id}}" name="{{properties.address}}" id="fivepost_button"><i class="fa fa-check" aria-hidden="true"></i> Выбрать</button>' +
                    '</div>',
                {
                    build: function () {
                        BalloonContentLayout.superclass.build.call(this);
                        $("#fivepost_button").bind("click", this.onCounterClick);
                    },
					clear: function () {
                        $("#fivepost_button").unbind("click", this.onCounterClick);
                        BalloonContentLayout.superclass.clear.call(this);
						
						$(document).on("click", "#fivepost_button", function (event) {
							$('#fivepost-adr-for-radio').remove();
							$('<b id="fivepost-adr-for-radio"></br>' + $(this).attr("name") + '</b>').insertBefore("#fivepost-hide-pvz");
							
							$('.fivepost-min-price').text($(this).attr("price"));
							$('.fivepost-terms').text($(this).attr("terms"));
							
							$.post("index.php?route=extension/shipping/5post/save", {
								address: $(this).attr("name"),
								id: $(this).attr("dpi"),
								rate_id: $(this).attr("rt"),
							}).done(function (data) {});
							
							$('[name="shipping_address[address_1]"], [name="address_1"]').val($(this).attr("name"));
							
							if ($("input[name=address_same]").is(":checked")) {
								$('[name="payment_address[address_1]"]').val($(this).attr("name"));
							}
						});
                    },
					onCounterClick: function () {
						$("#fivepost-modal").modal("hide");
                    },
                }
            );
						
            objectManager = new ymaps.ObjectManager({
                clusterize: true,
                clusterHasBalloon: true,
            });
				
            objectManager.clusters.options.set({
                gridSize: 50,
                preset: 'islands#ClusterIcons',
				clusterIconColor: '#61BC47',
                hasBalloon: false,
                groupByCoordinates: false,
                clusterDisableClickZoom: false,
                maxZoom: 8,
                zoomMargin: [45],
                clusterHideIconOnBalloonOpen: false,
                geoObjectHideIconOnBalloonOpen: false,
            });
			
            objectManager.objects.options.set({
                balloonContentLayout: BalloonContentLayout,
            });

            fivepostMap.geoObjects.add(objectManager);

            var filter_down = "";

            /* действие при закрытии всплывающего окна */
            $("#fivepost-modal").on("hidden.bs.modal", function (e) {
                fivepostMap.destroy();

                $(this).remove();

                if (typeof reloadAll == "function") {
                    setTimeout(function () {
                        reloadAll();
                    }, 500);
                }
            });

            $.post("index.php?route=extension/shipping/5post/getPoints", {
                filter_down: filter_down,
            }).done(function (data) {
				$("#fivepost-open-pvz").removeAttr("disabled");
				$("#fivepost-open-pvz").button('reset');
				
				$("#fivepost-open-pvz-simple").removeAttr("disabled");
				$("#fivepost-open-pvz-simple").button('reset');
				
                objectManager.add(data);

                if (data.position) {
                    $("#fivepost-modal").modal("show");
                    $("#fivepost-modal .fivepost-modal-title").html(
                        '<div style="background-size: auto 90%; line-height:38px; height:38px;">Точки выдачи</div>'
                    );
                    fivepostMap.setCenter(
                        [data.position.location[0], data.position.location[1]],
                        data.features.length == 1 ? 13 : 10
                    );
                }

                /* создаем макет левого блока со списком адресов ПВЗ */
                var template = "";

                template += '<div class="tab-content">';
                template += '<div class="tab-pane active">';
                if (data.features) {
                    $.each(data.features, function (i, e) {
                        template +=
                            '<div class="fivepost-filter fivepost-list-group-item" data-point-id="' +
                            e.properties.id +
                            '" data-location="' +
                            e.geometry.coordinates +
                            '">';
                        template +=
                            '<div style="font-size:12px;">' +
                            e.properties.address +
                            "</div>";
                        template += "</div>";
                    });
                }
                template += "</div>";
                template += "</div>";
                $("#fivepost-filter_content").html(template);

                /* действие по клику на элемент списка ПВЗ */
                $(document).on("click", ".fivepost-filter", function (event) {
                    $(".fivepost-filter").removeClass("fivepost-point-active");
					$(".toggle-tab-content").click();

                    $(this).addClass("fivepost-point-active");

                    var objectId = $(this).attr("data-point-id"),
                        location = $(this).attr("data-location").split(",");

                    objectManager.objects.balloon.open(objectId);

                    if (objectManager.objects.balloon.isOpen(objectId)) {
                        fivepostMap.setCenter(location, 13);
                    }
                });

                /* действие по клику на метку */
                objectManager.objects.events.add("click", function (e) {
                    var objectId = e.get("objectId"),
                        button = $('[data-point-id="' + objectId + '"]');

                    $(".fivepost-filter").removeClass("fivepost-point-active");

                    button.addClass("fivepost-point-active");

                    if ($(window).width() >= 767) {
                        $("#fivepost-filter_content").scrollTo(button, 300);
                    }
                });
            });
        }
    }
}

$(document).on("keyup", '[name="city"], [name="shipping_address[city]"]', function() {
	$("#content .dropdown-menu").remove();
	
	$('input[name=\'city\'], input[name=\'shipping_address[city]\']').autocomplete({
		'source': function(request, response) {
		
			var fn = function(){
				$progress = $.ajax({
					url: 'index.php?route=extension/shipping/5post/autocomplete&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						
						response($.map(json, function(item) {
								return {
									label: item['name'],
									value:   item['value'],
									zone_id:   item['zone_id'],
									country_id:   item['country_id'],
								}
						}));
					},
				});
			};
			var interval = setTimeout(fn, 1000);
		},
		'select': function(item) {
			$('#input-payment-city').val(item['value']);
			$('#input-shipping-city').val(item['value']);
			$('[name="city"]').val(item['value']);
			
			if(item['country_id']){
				$('#shipping_address_country_id').val(item['country_id']);
				var array = [];
				var i = 0;
				$('#input-payment-country option').each(function() {
					array[i] = $(this).val();
					i++;
				});
				
				if(array.length != 0){
					document.getElementById('input-payment-country').selectedIndex = array.indexOf(item['country_id']);
					document.getElementById('input-payment-country').dispatchEvent(new Event('change'));
				}
			}
			
			setTimeout(function() {
				if(item['zone_id']){
					$('#input-payment-zone').val(item['zone_id']);
					$('#input-shipping-zone').val(item['zone_id']);
				}
			}, 500);
			
			$.post("index.php?route=extension/shipping/5post/save", {
				country_id: item['country_id'],
				zone_id: item['zone_id']
			}).done(function(data) {		
				setTimeout(function() {
					//reloadAll()
				}, 500);
			});
			
		}
	});
});