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
			
			
			var fivepostModalMap = $('<div class="fivepost-modal fivepost-fade" id="fivepost-modal" tabindex="-1" role="dialog"></div>');

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
								
			fivepostModalMap.append(fivepostModalContent);

			if($(window).width() > 767) {
				fivepostModalMap.find('.fivepost-modal-dialog').width(width);
				fivepostModalMap.find('.fivepost-pvz-list').height(height);
				fivepostModalMap.find('#fivepost_map').height(height);
			}

			var fivepostMap;

			$(document).on("click", "#fivepost-open-pvz", function () {
				Fivepost.points($(this).attr("oi"), $(this).attr("pi"), $(this).attr("ct"), $(this).attr("ut"));
			});
							
			var Fivepost = {
				points: function (order_id, receiver_location, catalog, user_token) {
					$("#fivepost-open-pvz").attr("disabled","disabled");
					$("#fivepost-open-pvz").button('loading');
								
					$("#fivepost-modal").remove();

					$("body").append(fivepostModalMap);
					ymaps.ready(init);

					function init() {
						fivepostMap = new ymaps.Map(
							"fivepost_map",
							{
								center: [55.76, 37.64],
								zoom: 9,
								controls: ["zoomControl"],
							},
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
								'<b>Адресс пункта выдачи заказов:</b></br><h4 style="margin-top: 0px;">$[properties.address]</h4>' +
								'<b>Время работы:</b></br>' +
								'[if properties.workingHours.day0]<i class="fa fa-clock-o" aria-hidden="true"></i> $[properties.workingHours.day0]</br>[endif]' +
								'[if properties.workingHours.day1]<i class="fa fa-clock-o" aria-hidden="true"></i> $[properties.workingHours.day1]</br>[endif]' +
								'[if properties.workingHours.day2]<i class="fa fa-clock-o" aria-hidden="true"></i> $[properties.workingHours.day2]</br>[endif]' +
								'[if properties.workingHours.day3]<i class="fa fa-clock-o" aria-hidden="true"></i> $[properties.workingHours.day3]</br>[endif]' +
								'[if properties.workingHours.day4]<i class="fa fa-clock-o" aria-hidden="true"></i> $[properties.workingHours.day4]</br>[endif]' +
								'[if properties.workingHours.day5]<i class="fa fa-clock-o" aria-hidden="true"></i> $[properties.workingHours.day5]</br>[endif]' +
								'[if properties.workingHours.day6]<i class="fa fa-clock-o" aria-hidden="true"></i> $[properties.workingHours.day6][endif]' +
								'[if properties.description]<p style="margin: 2px 0px 0px 0px;"><b>Описание:</b></p><h4 style="margin-top: 0px;">$[properties.description]</h4>[endif]' +
								'<b>Способы оплаты:</b></br>' +
								'[if properties.cashAllowed == 1]Расчет наличными</br>[endif]' +
								'[if properties.cardAllowed == 1]Расчет картой</br>[endif]' +
								'<b class="fivepost-price-title">Стоимость: </b><i class="fivepost-price">$[properties.price]</i>' +
								'<button type="button" class="btn btn-outline-secondary btn-sm" dpi="$[properties.id]" terms="$[properties.terms]" priceInt="$[properties.priceInt]" price="$[properties.price]" rt="$[properties.rate_id]" name="$[properties.address]" id="fivepost_button"><i class="fa fa-check" aria-hidden="true"></i> Выбрать</button>' +
								'</div>',
							{
								build: function () {
									BalloonContentLayout.superclass.build.call(this);
									//$("#fivepost_button").bind("click", this.onCounterClick);
								},
								clear: function () {
									//$("#fivepost_button").unbind("click", this.onCounterClick);
									BalloonContentLayout.superclass.clear.call(this);
													
									$(document).on("click", "#fivepost_button", function (event) {
									
										$('#fivepost-address').text($(this).attr("name"));
										$('#delivery_cost').val($(this).attr("priceInt"));
										
										$.post("index.php?route=extension/shipping/5post/validPointPayment&user_token=" + user_token, {
											map_order_id: order_id, receiver_location: $(this).attr("dpi")
										}).done(function (data) {
											
											if (typeof data.error !== 'undefined') {
												alert(data.error);
											}
										})
										
										$.post(catalog + "index.php?route=extension/shipping/5post/saveAdmin", {
											pointId: $(this).attr("dpi"),
											delivery_cost: $(this).attr("priceInt"),
											order_id: order_id,
										}).done(function (data) {
											$("#fivepost-modal").modal("hide");									
										});
										
									});
								},
								/*onCounterClick: function () {
									$("#fivepost-modal").modal("hide");
								},*/
							}
						);
													
						objectManager = new ymaps.ObjectManager({
							clusterize: true,
							clusterHasBalloon: true,
						});
										
						objectManager.objects.options.set({
							balloonContentLayout: BalloonContentLayout,
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
																				
						fivepostMap.geoObjects.add(objectManager);

						var filter_down = "";

						/* действие при закрытии всплывающего окна */
						$("#fivepost-modal").on("hidden.bs.modal", function (e) {
							fivepostMap.destroy();

							$(this).remove();
							$("body").addClass("modal-open");
						});
										
						$.post(catalog + "index.php?route=extension/shipping/5post/getPointsAdmin&pointId=" + receiver_location + "&order_id=" + order_id, {
						}).done(function (data) {
							$("#fivepost-open-pvz").removeAttr("disabled");
							$("#fivepost-open-pvz").button('reset');
											
							objectManager.add(data);
							
							if (data.position) {
								$("#fivepost-modal").modal("show");
								$("#fivepost-modal .fivepost-modal-title").html(
									'<div style="background-size: auto 90%; line-height:38px; height:38px;">Пункты самовывоза</div>'
								);
						
								fivepostMap.setCenter(
									[data.position.location[0], data.position.location[1]],
									data.features.length == 1 ? 13 : 10
								);
							}else{
								alert('По данным габаритам не были найдены подходящие точки!');
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
										
								objectManager.objects.balloon.open(objectId);
								button.addClass("fivepost-point-active");

								if ($(window).width() >= 767) {
									$("#fivepost-filter_content").scrollTo(button, 300);
								}
							});
						});
					}
				}
			}