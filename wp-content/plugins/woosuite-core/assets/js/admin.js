(function ($) {
    let wizardSelectedPlugins = [],
        wizardSelectedNotIncludedPlugins = [],
        wizardSelectedType = '',
        stepToInstallPluginHtml = '',
        shouldManuallyTriggerFinishAction = false;
    let moduleSwitchRequest = function (data, btn) {
        $.post(woosuite_core_admin.ajax_url, data)
            .done(function (r) {
                if (r.success && r.nonce) {
                    btn.data('nonce', r.nonce);
                }

                if (r.success) {
                    if (data.action === 'woosuite_core_activate_module') {
                        $.post(woosuite_core_admin.ajax_url, {slug: data.slug, action: 'woosuite_core_module_footer'})
                            .done(function (r) {
                                btn.parent('.footer').html(r.footer);
                            })
                            .complete(function () {
                                btn.removeClass('loading');
                                $('.woosuite-core-module-switch').removeClass('loading');
                            });
                    } else {
                        btn.parent('.footer').html(r.footer);
                    }
                }

                if (!r.success && r.message) {
                    $('.woosuite-core-module-switch').removeClass('loading');
                    btn.toggleClass('switch-on switch-off');
                    alert(r.message);
                }
            })
            .complete(function () {
                $('.woosuite-core-module-switch').removeClass('loading');
            });
    };

    let moduleInstallRequest = function (data, btn) {
        $.post(woosuite_core_admin.ajax_url, data)
            .done(function (r) {
                if (r.success) {
                    $.post(woosuite_core_admin.ajax_url, {slug: data.slug, action: 'woosuite_core_module_footer'})
                        .done(function (r) {
                            btn.parent('.footer').html(r.footer);
                        })
                        .complete(function () {
                            btn.removeClass('loading');
                        });
                } else if (r.data.errorMessage) {
                    btn.removeClass('loading');
                    alert(r.data.errorMessage);
                }
            })
            .complete(function () {
                $('.install-btn').not(btn).removeClass('loading');
            })
    };

    $(document).ready(function () {

        $(document)
            .on('click', '.install-btn', function (e) {
                e.preventDefault;
                let btn = $(this);

                if (btn.hasClass('loading')) {
                    return false;
                }

                $('.install-btn').addClass('loading');

                moduleInstallRequest({
                    'slug': btn.data('slug'),
                    'action': 'install-plugin',
                    '_wpnonce': btn.data('nonce')
                }, btn);
                return false;
            })
            .on('click', '.woosuite-core-module-switch', function (e) {
                e.preventDefault;
                let btn = $(this);

                if (btn.hasClass('loading')) {
                    return false;
                }

                $('.woosuite-core-module-switch').addClass('loading');

                if (btn.hasClass('switch-on')) {
                    btn.toggleClass('switch-on switch-off');
                    moduleSwitchRequest({
                        'slug': btn.data('slug'),
                        'action': 'woosuite_core_deactivate_module',
                        'nonce': btn.data('nonce')
                    }, btn);
                } else {
                    btn.toggleClass('switch-on switch-off');
                    moduleSwitchRequest({
                        'slug': btn.data('slug'),
                        'action': 'woosuite_core_activate_module',
                        'nonce': btn.data('nonce')
                    }, btn);
                }
                return false;
            })
            .on('click', '.woosuite-save-master-button', function () {
                window.onbeforeunload = null;
                let $form = $(".woosuite-master-form form");

                // .submit() does not work if the form contains and element that has id or name "submit"
                if ($form.find('#submit').length) {
                    // Trigger click
                    $form.find('#submit').trigger('click');
                } else if ($form.find('[name="submit"]').length) {
                    // Trigger click
                    $form.find('[name="submit"]').trigger('click');
                } else {
                    // Form submit
                    $form.submit();
                }
            })
            .on('click', '.add-new-license-btn', function (e) {
                e.preventDefault;

                let html = '<tr><th scope="row"><label for="license_key">License Key</label></th><td>';
                html += '<input name="new_license_key" type="text" id="new_license_key" value="" class="regular-text" required />';
                html += '<p class="submit">';
                html += '<button type="submit" name="action" value="woosuite_core_add_new_license" class="button button-primary">Activate License</button>';
                html += '</p>';
                html += '</td></tr>';
                $(html).insertAfter($(this).closest('tr'));
            })
            .on('click', '.deactivate_new_license', function (e) {
                e.preventDefault
                $(this).closest('p').siblings('input').attr('disabled', false);
            })
            .on('click', '.update-clicks', function () {
                $.get(woosuite_core_admin.api_url + '/click/' + $(this).attr('data-slug'));
            })
            .on("click", ".wizard-wrapper ul li a", function () {
                if (shouldManuallyTriggerFinishAction &&
                    $(this).attr("href") === "#finish" &&
                    isWizardFinished()) {
                    window.location.href = window.woosuiteCore.wizardRedirectUrl;
                }
            })
            .on("mousedown touchstart", function (e) {
                let $filterEle = $('.woosuite-user-roles-filter');
                if (!$filterEle.is(e.target) && $filterEle.has(e.target).length === 0) {
                    $filterEle.hide();
                }
            })
            .on("click", ".control-exit span", function () {
                $(".wizard-wrapper").css("display", "none");
				$("body").removeClass("wizard-showing");
            })
            .on("change", ".enabled_functions", function () {
                let allCheckedEle = updateNextButtonState();
                wizardSelectedPlugins = [];
                wizardSelectedNotIncludedPlugins = [];
                if (allCheckedEle.length) {
                    allCheckedEle.each(function () {
                        let pluginData = {
                            'slug': $(this).val(),
                            'name': $(this).data("name"),
                            'path': $(this).data("path"),
                            'nonce': $(this).data("nonce"),
                            'homepage': $(this).data("homepage"),
                            'activateNonce': $(this).data("activate-nonce"),
                        };
                        if ($(this).hasClass("not-included")) {
                            wizardSelectedNotIncludedPlugins.push(pluginData);
                        } else {
                            wizardSelectedPlugins.push(pluginData);
                        }
                    })
                }
                $(this).parent().toggleClass("active");
            })
            .on('keyup', function (e) {
                if (e.keyCode === 27) {
                    $(".wizard-wrapper").css("display", "none");
                }
            });
    });

    /* Custom Accordion */
    $(function () {
        $('.acc__title').click(function (j) {
            let dropDown = $(this).closest('.acc__card').find('.acc__panel');
            $(this).closest('.acc').find('.acc__panel').not(dropDown).slideUp();

            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                $(this).closest('.acc').find('.acc__title.active').removeClass('active');
                $(this).addClass('active');
            }
            dropDown.stop(false, true).slideToggle();
            j.preventDefault();
        });
    });

    $(document).ready(function () {
        if ($("#woosuite-core-chart").length) {
            const woosuiteChart = new Chart(
                document.getElementById('woosuite-core-chart'),
                {
                    labels: '',
                    type: 'bar',
                    data: {
                        datasets: window.chartData
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                        },
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                }
            );

            getChartDataAjax(woosuiteChart, woosuite_core_admin.b2b_applied_date_range);

            $("#ws-filter-date-range").on("change", function () {
                getChartDataAjax(woosuiteChart, $(this).val());
            })

            $(".user-roles-filter-icon").on("click", function () {
                $(".woosuite-user-roles-filter").fadeIn();
            })

            $(".user-roles-filter-icon-close").on("click", function () {
                $(".woosuite-user-roles-filter").hide();
            })

            $("#user-role-filter-apply").on("click", function (e) {
                e.preventDefault();
                let checkedUserRoles =
                    $('input:checkbox[name="user-roles-filter[]"]:checked')
                        .map(function () {
                            return $(this).val();
                        }).get();
                $.ajax({
                    type: "POST",
                    url: woosuiteCore.ajaxUrl,
                    data: {
                        action: "woosuite_update_user_roles",
                        selected_user_roles: checkedUserRoles,
                        range: $("#ws-filter-date-range").val()
                    },
                    dataType: "json",
                    success: function (result, textStatus, jqXHR) {
                        //Hide the filter
                        $(".woosuite-user-roles-filter").hide();

                        //Update Chart data
                        woosuiteChart.data.datasets = result.data.chartData;
                        woosuiteChart.update();

                        //Update Revenue data
                        $("#woosuite-widget-b2b-revenue .card-value").html(result.data.b2bRevenue);

                        //Update Customer count data
                        $("#woosuite-widget-b2b-customers .card-value").html(result.data.b2bCustomer);
                    }
                })
            })
        }

        /* Wizard form */
        if ($("#wizard").length) {
            $("#wizard").steps({
                headerTag: "h2",
                bodyTag: "section",
                transitionEffect: "fade",
                transitionEffectSpeed: 300,
                labels: {
                    finish: "Finish",
                    next: "Next",
                    previous: "Back"
                },
                onInit: function (event, current) {
                    //Hide the Back button at the first step
                    $('.actions > ul > li:first-child').attr('style', 'display:none');
                    //Save the HTML content of the last step that installing plugins (we need it in case we want to add that step back after removed)
                    stepToInstallPluginHtml = $("#wizard section:last-child").html();
                },
                onStepChanging: function (event, currentIndex, newIndex) {

                    //Show/hide the plugin items based on selected type at the first step
                    if (currentIndex === 0 && newIndex === 1) {
                        wizardSelectedType = $("input[name='market'][type='radio']:checked").val();
                        if (wizardSelectedType !== "") {
                            $(".available-functions > .button").each(function () {

                                let currentType = $(this).data("type");

                                if (!$.isArray(currentType)) {
                                    $(this).hide();
                                }
                                if ($.inArray(wizardSelectedType, currentType) !== -1) {
                                    $(this).show();
                                } else {
                                    $(this).hide();
                                }
                            })
                        }

                    }

                    if (2 === newIndex) {
                        //If we only chose not-included plugin and do not chose any valid plugins
                        if (Object.keys(wizardSelectedNotIncludedPlugins).length > 0 &&
                            Object.keys(wizardSelectedPlugins).length === 0) {

                            //Removing last step (the step to install plugin), because we do not have any plugins to install
                            $("#wizard").steps("remove", 3);

                            //The onFinishing and onFinished events are not fired after add/remove step, so we need to set a flag to handle it manually
                            shouldManuallyTriggerFinishAction = true;

                            //Set the list of not-included plugins to show it in the right sidebar at the dashboard page
                            updateSelectedNotIncludedPlugin({
                                'listOfPlugin': wizardSelectedNotIncludedPlugins,
                                'action': 'update-selected-not-included-plugin'
                            });

                        } else if ($("#wizard > .content > section").length === 3) {
                            //If we chose any valid plugin and the last step is removed, we need to add it back
                            $("#wizard").steps("add", {
                                content: stepToInstallPluginHtml
                            });

                            //onFinishing and onFinished events are fired normally so we do not need to handle it
                            shouldManuallyTriggerFinishAction = false;
                        }
                    }
                    if (newIndex < currentIndex) {
                        return true;
                    }

                    //If we did not chose any plugin item -> we do not allow to go to next step
                    return !(1 === currentIndex && !Object.keys(wizardSelectedPlugins).length && !Object.keys(wizardSelectedNotIncludedPlugins).length);
                },
                onStepChanged: function (event, currentIndex, priorIndex) {
                    //On last step to install plugin, hide the back button and disable finish button
                    if (currentIndex === 3) {
                        disableBackButton();
                        disableFinishButton();
                    } else {
                        enableBackButton();
                    }

                    if (currentIndex > 0 && currentIndex < 3) {
                        showBackButton();
                    } else {
                        hideBackButton();
                    }

                    let numberOfSelectedPlugins = Object.keys(wizardSelectedPlugins).length;

                    if (3 === currentIndex) {
                        updateSelectedNotIncludedPlugin({
                            'listOfPlugin': wizardSelectedNotIncludedPlugins,
                            'action': 'update-selected-not-included-plugin'
                        });

                        //Install the selected valid plugin
                        if (numberOfSelectedPlugins) {
                            let percentageEachStep = Math.round(100 / (numberOfSelectedPlugins * 2));
                            for (let x = 0; x < numberOfSelectedPlugins; x++) {
                                (function (x) {
                                    setTimeout(function () {
                                        moduleInstallRequestViaWizard({
                                            'slug': wizardSelectedPlugins[x].slug,
                                            'action': 'install-plugin',
                                            '_wpnonce': wizardSelectedPlugins[x].nonce
                                        }, percentageEachStep, wizardSelectedPlugins[x]);
                                    }, x * 4000);
                                })(x);
                            }
                        }
                    }
                    if (1 === currentIndex) {
                        updateNextButtonState();
                    }
                },
                onFinishing: function (event, currentIndex) {
                    //We only allow to finish the wizard when the installing plugin task is done
                    return isWizardFinished();

                },
                onFinished: function (event, currentIndex) {
                    //Redirecting to the dashboard page after finished
                    window.location.href = window.woosuiteCore.wizardRedirectUrl;
                }
            });
        }

        $('.wizard > .steps li a').click(function () {
            $(this).parent().addClass('checked');
            $(this).parent().prevAll().addClass('checked');
            $(this).parent().nextAll().removeClass('checked');
        });

        // Custom Jquery Step Button
        $('.forward').click(function () {
            $("#wizard").steps('next');
        })

        $('.backward').click(function () {
            $("#wizard").steps('previous');
        })

        // Select Dropdown
        $('html').click(function () {
            $('.select .dropdown').hide();
        });

        $('.select').click(function (event) {
            event.stopPropagation();
        });

        $('.select .select-control').click(function () {
            $(this).parent().next().toggle();
        })

        $('.select .dropdown li').click(function () {
            $(this).parent().toggle();
            let text = $(this).attr('rel');
            $(this).parent().prev().find('div').text(text);
        })

        $(".wizard-btn").on("click", function (e) {
            e.preventDefault();
			$(".wizard-wrapper").css("display","block");
			$("body").addClass("wizard-showing");
        })
        reportB2bCustomerAjax();
        reportPendingApplicationAjax();
        reportNewQuotesAjax();
        reportRecentlyUserTableAjax();
    });

    function getChartDataAjax(woosuiteChart, dateRange) {
        $.ajax({
            type: "POST",
            url: woosuiteCore.ajaxUrl,
            data: {
                action: "woosuite_update_chart_report",
                range: dateRange
            },
            dataType: "json",
            success: function (result, textStatus, jqXHR) {
                woosuiteChart.data.datasets = result.data.chartData;
                woosuiteChart.update();

                $("#woosuite-widget-b2b-revenue .card-value").html(result.data.totalRevenue);
            }
        })
    }

    function reportRecentlyUserTableAjax() {
        let recentlyUserEle = $("body").find("#woosuite-reporting-pending-user");
        if (recentlyUserEle.length) {
            $.ajax({
                type: "POST",
                url: woosuiteCore.ajaxUrl,
                data: {
                    action: "woosuite_report_recently_user_table",
                },
                dataType: "json",
                success: function (result, textStatus, jqXHR) {
                    recentlyUserEle.replaceWith(result.data.value);
                }
            })
        }
    }

    function reportB2bCustomerAjax() {
        let b2bCustomerEle = $("body").find("#woosuite-widget-b2b-customers");
        if (b2bCustomerEle.length) {
            $.ajax({
                type: "POST",
                url: woosuiteCore.ajaxUrl,
                data: {
                    action: "woosuite_report_b2b_customer",
                },
                dataType: "json",
                success: function (result, textStatus, jqXHR) {
                    b2bCustomerEle.find(".card-value").html(result.data.value);
                }
            })
        }
    }

    function reportPendingApplicationAjax() {
        let pendingApplicationEle = $("body").find("#woosuite-widget-pending-application");
        if (pendingApplicationEle.length) {
            $.ajax({
                type: "POST",
                url: woosuiteCore.ajaxUrl,
                data: {
                    action: "woosuite_report_pending_application",
                },
                dataType: "json",
                success: function (result, textStatus, jqXHR) {
                    pendingApplicationEle.find(".card-value").html(result.data.value);
                }
            })
        }
    }

    function reportNewQuotesAjax() {
        let newQuotesEle = $("body").find("#woosuite-widget-new-quotes");
        if (newQuotesEle.length) {
            $.ajax({
                type: "POST",
                url: woosuiteCore.ajaxUrl,
                data: {
                    action: "woosuite_report_new_quotes",
                },
                dataType: "json",
                success: function (result, textStatus, jqXHR) {
                    newQuotesEle.find(".card-value").html(result.data.value);
                }
            })
        }
    }

    function updateNextButtonState() {
        let allCheckedEle = $('.enabled_functions:checkbox:checked');
        if (allCheckedEle.length) {
            enableNextButton();
        } else {
            disableNextButton();
        }
        return allCheckedEle;
    }

    function updateCircleProcess(percentageEachStep) {
        let circleEle = $(".circle-installing"),
            currentClass = circleEle.attr("class"),
            numberPercentageEle = circleEle.find("> span"),
            newPercentageNumber = parseInt(numberPercentageEle.html().replace("%", "")) + percentageEachStep;
        newPercentageNumber = newPercentageNumber > 100 ? 100 : newPercentageNumber;
        let removedOldNumberClass = (currentClass.match(/(^|\s)p\S+/g) || []).join(' ');

        circleEle.attr("class", currentClass.replace(removedOldNumberClass, '') + " p" + newPercentageNumber);
        numberPercentageEle.html(newPercentageNumber + "%");

        if (100 === newPercentageNumber) {
            $(".plugin-install-status").html("<p>It's done</p>");
            enableFinishButton();
        }
    }

    function moduleInstallRequestViaWizard(data, percentageEachStep, pluginData) {
        updateCircleProcess(percentageEachStep);
        $.post(woosuiteCore.ajaxUrl, data)
            .done(function (r) {
                if (r.success || r.data.errorCode === "folder_exists") {
                    $.post(woosuiteCore.ajaxUrl, {
                        "action": "woo_activate_plugin",
                        "slug": pluginData.slug,
                        "file_path": pluginData.path,
                        "_wpnonce": pluginData.activateNonce
                    }, function (data, status) {
                        updateCircleProcess(percentageEachStep);
                    });
                }
            })
            .complete(function () {

            })
    }

    function updateSelectedNotIncludedPlugin(data) {
        $.post(woosuiteCore.ajaxUrl, data)
    }

    function disableNextButton() {
        disableButton(2);
    }

    function enableNextButton() {
        enableButton(2);
    }

    function disableBackButton() {
        disableButton(1);
    }

    function hideBackButton() {
        $('.actions > ul > li:first-child').attr('style', 'display:none');
    }


    function showBackButton() {
        $('.actions > ul > li:first-child').attr('style', '');
    }

    function enableBackButton() {
        enableButton(1);
    }

    function disableFinishButton() {
        disableButton(3);
    }

    function enableFinishButton() {
        enableButton(3);
    }


    function disableButton(index) {
        $(".actions ul li:nth-child(" + index + ")").addClass("disabled").attr("aria-disabled", "true");
    }

    function enableButton(index) {
        $(".actions ul li:nth-child(" + index + ")").removeClass("disabled").attr("aria-disabled", "false");
    }

    function isWizardFinished() {
        $allSections = $("#wizard").find("section").length;
        return $allSections === 3 ||
            ($allSections === 4 && $(".circle-installing").hasClass("p100"));
    }
})(jQuery);
