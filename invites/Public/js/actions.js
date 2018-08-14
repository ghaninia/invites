! function(a, b) {
    "use strict";
    "function" == typeof define && define.amd ? define([], function() {
        return b.apply(a)
    }) : "object" == typeof exports ? module.exports = b.call(a) : a.Waves = b.call(a)
}("object" == typeof global ? global : this, function() {
    "use strict";

    function a(a) {
        return null !== a && a === a.window
    }

    function b(b) {
        return a(b) ? b : 9 === b.nodeType && b.defaultView
    }

    function c(a) {
        var b = typeof a;
        return "function" === b || "object" === b && !!a
    }

    function d(a) {
        return c(a) && a.nodeType > 0
    }

    function e(a) {
        var b = m.call(a);
        return "[object String]" === b ? l(a) : c(a) && /^\[object (Array|HTMLCollection|NodeList|Object)\]$/.test(b) && a.hasOwnProperty("length") ? a : d(a) ? [a] : []
    }

    function f(a) {
        var c, d, e = {
                top: 0,
                left: 0
            },
            f = a && a.ownerDocument;
        return c = f.documentElement, "undefined" != typeof a.getBoundingClientRect && (e = a.getBoundingClientRect()), d = b(f), {
            top: e.top + d.pageYOffset - c.clientTop,
            left: e.left + d.pageXOffset - c.clientLeft
        }
    }

    function g(a) {
        var b = "";
        for (var c in a) a.hasOwnProperty(c) && (b += c + ":" + a[c] + ";");
        return b
    }

    function h(a, b, c) {
        if (c) {
            c.classList.remove("waves-rippling");
            var d = c.getAttribute("data-x"),
                e = c.getAttribute("data-y"),
                f = c.getAttribute("data-scale"),
                h = c.getAttribute("data-translate"),
                i = Date.now() - Number(c.getAttribute("data-hold")),
                j = 350 - i;
            0 > j && (j = 0), "mousemove" === a.type && (j = 150);
            var k = "mousemove" === a.type ? 2500 : o.duration;
            setTimeout(function() {
                var a = {
                    top: e + "px",
                    left: d + "px",
                    opacity: "0",
                    "-webkit-transition-duration": k + "ms",
                    "-moz-transition-duration": k + "ms",
                    "-o-transition-duration": k + "ms",
                    "transition-duration": k + "ms",
                    "-webkit-transform": f + " " + h,
                    "-moz-transform": f + " " + h,
                    "-ms-transform": f + " " + h,
                    "-o-transform": f + " " + h,
                    transform: f + " " + h
                };
                c.setAttribute("style", g(a)), setTimeout(function() {
                    try {
                        b.removeChild(c)
                    } catch (a) {
                        return !1
                    }
                }, k)
            }, j)
        }
    }

    function i(a) {
        if (q.allowEvent(a) === !1) return null;
        for (var b = null, c = a.target || a.srcElement; null !== c.parentElement;) {
            if (c.classList.contains("waves-effect") && !(c instanceof SVGElement)) {
                b = c;
                break
            }
            c = c.parentElement
        }
        return b
    }

    function j(a) {
        var b = i(a);
        if (null !== b) {
            if (b.disabled || b.getAttribute("disabled") || b.classList.contains("disabled")) return;
            if (q.registerEvent(a), "touchstart" === a.type && o.delay) {
                var c = !1,
                    d = setTimeout(function() {
                        d = null, o.show(a, b)
                    }, o.delay),
                    e = function(e) {
                        d && (clearTimeout(d), d = null, o.show(a, b)), c || (c = !0, o.hide(e, b))
                    },
                    f = function(a) {
                        d && (clearTimeout(d), d = null), e(a)
                    };
                b.addEventListener("touchmove", f, !1), b.addEventListener("touchend", e, !1), b.addEventListener("touchcancel", e, !1)
            } else o.show(a, b), n && (b.addEventListener("touchend", o.hide, !1), b.addEventListener("touchcancel", o.hide, !1)), b.addEventListener("mouseup", o.hide, !1), b.addEventListener("mouseleave", o.hide, !1)
        }
    }
    var k = k || {},
        l = document.querySelectorAll.bind(document),
        m = Object.prototype.toString,
        n = "ontouchstart" in window,
        o = {
            duration: 750,
            delay: 200,
            show: function(a, b, c) {
                if (2 === a.button) return !1;
                b = b || this;
                var d = document.createElement("div");
                d.className = "waves-ripple waves-rippling", b.appendChild(d);
                var e = f(b),
                    h = 0,
                    i = 0;
                "touches" in a && a.touches.length ? (h = a.touches[0].pageY - e.top, i = a.touches[0].pageX - e.left) : (h = a.pageY - e.top, i = a.pageX - e.left), i = i >= 0 ? i : 0, h = h >= 0 ? h : 0;
                var j = "scale(" + b.clientWidth / 100 * 3 + ")",
                    k = "translate(0,0)";
                c && (k = "translate(" + c.x + "px, " + c.y + "px)"), d.setAttribute("data-hold", Date.now()), d.setAttribute("data-x", i), d.setAttribute("data-y", h), d.setAttribute("data-scale", j), d.setAttribute("data-translate", k);
                var l = {
                    top: h + "px",
                    left: i + "px"
                };
                d.classList.add("waves-notransition"), d.setAttribute("style", g(l)), d.classList.remove("waves-notransition"), l["-webkit-transform"] = j + " " + k, l["-moz-transform"] = j + " " + k, l["-ms-transform"] = j + " " + k, l["-o-transform"] = j + " " + k, l.transform = j + " " + k, l.opacity = "1";
                var m = "mousemove" === a.type ? 2500 : o.duration;
                l["-webkit-transition-duration"] = m + "ms", l["-moz-transition-duration"] = m + "ms", l["-o-transition-duration"] = m + "ms", l["transition-duration"] = m + "ms", d.setAttribute("style", g(l))
            },
            hide: function(a, b) {
                b = b || this;
                for (var c = b.getElementsByClassName("waves-rippling"), d = 0, e = c.length; e > d; d++) h(a, b, c[d])
            }
        },
        p = {
            input: function(a) {
                var b = a.parentNode;
                if ("i" !== b.tagName.toLowerCase() || !b.classList.contains("waves-effect")) {
                    var c = document.createElement("i");
                    c.className = a.className + " waves-input-wrapper", a.className = "waves-button-input", b.replaceChild(c, a), c.appendChild(a);
                    var d = window.getComputedStyle(a, null),
                        e = d.color,
                        f = d.backgroundColor;
                    c.setAttribute("style", "color:" + e + ";background:" + f), a.setAttribute("style", "background-color:rgba(0,0,0,0);")
                }
            },
            img: function(a) {
                var b = a.parentNode;
                if ("i" !== b.tagName.toLowerCase() || !b.classList.contains("waves-effect")) {
                    var c = document.createElement("i");
                    b.replaceChild(c, a), c.appendChild(a)
                }
            }
        },
        q = {
            touches: 0,
            allowEvent: function(a) {
                var b = !0;
                return /^(mousedown|mousemove)$/.test(a.type) && q.touches && (b = !1), b
            },
            registerEvent: function(a) {
                var b = a.type;
                "touchstart" === b ? q.touches += 1 : /^(touchend|touchcancel)$/.test(b) && setTimeout(function() {
                    q.touches && (q.touches -= 1)
                }, 500)
            }
        };
    return k.init = function(a) {
        var b = document.body;
        a = a || {}, "duration" in a && (o.duration = a.duration), "delay" in a && (o.delay = a.delay), n && (b.addEventListener("touchstart", j, !1), b.addEventListener("touchcancel", q.registerEvent, !1), b.addEventListener("touchend", q.registerEvent, !1)), b.addEventListener("mousedown", j, !1)
    }, k.attach = function(a, b) {
        a = e(a), "[object Array]" === m.call(b) && (b = b.join(" ")), b = b ? " " + b : "";
        for (var c, d, f = 0, g = a.length; g > f; f++) c = a[f], d = c.tagName.toLowerCase(), -1 !== ["input", "img"].indexOf(d) && (p[d](c), c = c.parentElement), -1 === c.className.indexOf("waves-effect") && (c.className += " waves-effect" + b)
    }, k.ripple = function(a, b) {
        a = e(a);
        var c = a.length;
        if (b = b || {}, b.wait = b.wait || 0, b.position = b.position || null, c)
            for (var d, g, h, i = {}, j = 0, k = {
                type: "mousedown",
                button: 1
            }, l = function(a, b) {
                return function() {
                    o.hide(a, b)
                }
            }; c > j; j++)
                if (d = a[j], g = b.position || {
                            x: d.clientWidth / 2,
                            y: d.clientHeight / 2
                        }, h = f(d), i.x = h.left + g.x, i.y = h.top + g.y, k.pageX = i.x, k.pageY = i.y, o.show(k, d), b.wait >= 0 && null !== b.wait) {
                    var m = {
                        type: "mouseup",
                        button: 1
                    };
                    setTimeout(l(m, d), b.wait)
                }
    }, k.calm = function(a) {
        a = e(a);
        for (var b = {
            type: "mouseup",
            button: 1
        }, c = 0, d = a.length; d > c; c++) o.hide(b, a[c])
    }, k.displayEffect = function(a) {
        k.init(a)
    }, k
});


;(function(root, factory) {

    if (typeof define === 'function' && define.amd) {
        define(factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.NProgress = factory();
    }

})(this, function() {
    var NProgress = {};

    NProgress.version = '0.2.0';

    var Settings = NProgress.settings = {
        minimum: 0.08,
        easing: 'linear',
        positionUsing: '',
        speed: 200,
        trickle: true,
        trickleSpeed: 200,
        showSpinner: true,
        barSelector: '[role="bar"]',
        spinnerSelector: '[role="spinner"]',
        parent: 'body',
        template: '<div class="bar" role="bar"><div class="peg"></div></div><div class="spinner" role="spinner"><div class="spinner-icon"></div></div>'
    };

    /**
     * Updates configuration.
     *
     *     NProgress.configure({
   *       minimum: 0.1
   *     });
     */
    NProgress.configure = function(options) {
        var key, value;
        for (key in options) {
            value = options[key];
            if (value !== undefined && options.hasOwnProperty(key)) Settings[key] = value;
        }

        return this;
    };

    /**
     * Last number.
     */

    NProgress.status = null;

    /**
     * Sets the progress bar status, where `n` is a number from `0.0` to `1.0`.
     *
     *     NProgress.set(0.4);
     *     NProgress.set(1.0);
     */

    NProgress.set = function(n) {
        var started = NProgress.isStarted();

        n = clamp(n, Settings.minimum, 1);
        NProgress.status = (n === 1 ? null : n);

        var progress = NProgress.render(!started),
            bar      = progress.querySelector(Settings.barSelector),
            speed    = Settings.speed,
            ease     = Settings.easing;

        progress.offsetWidth; /* Repaint */

        queue(function(next) {
            // Set positionUsing if it hasn't already been set
            if (Settings.positionUsing === '') Settings.positionUsing = NProgress.getPositioningCSS();

            // Add transition
            css(bar, barPositionCSS(n, speed, ease));

            if (n === 1) {
                // Fade out
                css(progress, {
                    transition: 'none',
                    opacity: 1
                });
                progress.offsetWidth; /* Repaint */

                setTimeout(function() {
                    css(progress, {
                        transition: 'all ' + speed + 'ms linear',
                        opacity: 0
                    });
                    setTimeout(function() {
                        NProgress.remove();
                        next();
                    }, speed);
                }, speed);
            } else {
                setTimeout(next, speed);
            }
        });

        return this;
    };

    NProgress.isStarted = function() {
        return typeof NProgress.status === 'number';
    };

    /**
     * Shows the progress bar.
     * This is the same as setting the status to 0%, except that it doesn't go backwards.
     *
     *     NProgress.start();
     *
     */
    NProgress.start = function() {
        if (!NProgress.status) NProgress.set(0);

        var work = function() {
            setTimeout(function() {
                if (!NProgress.status) return;
                NProgress.trickle();
                work();
            }, Settings.trickleSpeed);
        };

        if (Settings.trickle) work();

        return this;
    };

    /**
     * Hides the progress bar.
     * This is the *sort of* the same as setting the status to 100%, with the
     * difference being `done()` makes some placebo effect of some realistic motion.
     *
     *     NProgress.done();
     *
     * If `true` is passed, it will show the progress bar even if its hidden.
     *
     *     NProgress.done(true);
     */

    NProgress.done = function(force) {
        if (!force && !NProgress.status) return this;

        return NProgress.inc(0.3 + 0.5 * Math.random()).set(1);
    };

    /**
     * Increments by a random amount.
     */

    NProgress.inc = function(amount) {
        var n = NProgress.status;

        if (!n) {
            return NProgress.start();
        } else if(n > 1) {
            return;
        } else {
            if (typeof amount !== 'number') {
                if (n >= 0 && n < 0.2) { amount = 0.1; }
                else if (n >= 0.2 && n < 0.5) { amount = 0.04; }
                else if (n >= 0.5 && n < 0.8) { amount = 0.02; }
                else if (n >= 0.8 && n < 0.99) { amount = 0.005; }
                else { amount = 0; }
            }

            n = clamp(n + amount, 0, 0.994);
            return NProgress.set(n);
        }
    };

    NProgress.trickle = function() {
        return NProgress.inc();
    };

    /**
     * Waits for all supplied jQuery promises and
     * increases the progress as the promises resolve.
     *
     * @param $promise jQUery Promise
     */
    (function() {
        var initial = 0, current = 0;

        NProgress.promise = function($promise) {
            if (!$promise || $promise.state() === "resolved") {
                return this;
            }

            if (current === 0) {
                NProgress.start();
            }

            initial++;
            current++;

            $promise.always(function() {
                current--;
                if (current === 0) {
                    initial = 0;
                    NProgress.done();
                } else {
                    NProgress.set((initial - current) / initial);
                }
            });

            return this;
        };

    })();

    /**
     * (Internal) renders the progress bar markup based on the `template`
     * setting.
     */

    NProgress.render = function(fromStart) {
        if (NProgress.isRendered()) return document.getElementById('nprogress');

        addClass(document.documentElement, 'nprogress-busy');

        var progress = document.createElement('div');
        progress.id = 'nprogress';
        progress.innerHTML = Settings.template;

        var bar      = progress.querySelector(Settings.barSelector),
            perc     = fromStart ? '-100' : toBarPerc(NProgress.status || 0),
            parent   = document.querySelector(Settings.parent),
            spinner;

        css(bar, {
            transition: 'all 0 linear',
            transform: 'translate3d(' + perc + '%,0,0)'
        });

        if (!Settings.showSpinner) {
            spinner = progress.querySelector(Settings.spinnerSelector);
            spinner && removeElement(spinner);
        }

        if (parent != document.body) {
            addClass(parent, 'nprogress-custom-parent');
        }

        parent.appendChild(progress);
        return progress;
    };

    /**
     * Removes the element. Opposite of render().
     */

    NProgress.remove = function() {
        removeClass(document.documentElement, 'nprogress-busy');
        removeClass(document.querySelector(Settings.parent), 'nprogress-custom-parent');
        var progress = document.getElementById('nprogress');
        progress && removeElement(progress);
    };

    /**
     * Checks if the progress bar is rendered.
     */

    NProgress.isRendered = function() {
        return !!document.getElementById('nprogress');
    };

    /**
     * Determine which positioning CSS rule to use.
     */

    NProgress.getPositioningCSS = function() {
        // Sniff on document.body.style
        var bodyStyle = document.body.style;

        // Sniff prefixes
        var vendorPrefix = ('WebkitTransform' in bodyStyle) ? 'Webkit' :
            ('MozTransform' in bodyStyle) ? 'Moz' :
                ('msTransform' in bodyStyle) ? 'ms' :
                    ('OTransform' in bodyStyle) ? 'O' : '';

        if (vendorPrefix + 'Perspective' in bodyStyle) {
            // Modern browsers with 3D support, e.g. Webkit, IE10
            return 'translate3d';
        } else if (vendorPrefix + 'Transform' in bodyStyle) {
            // Browsers without 3D support, e.g. IE9
            return 'translate';
        } else {
            // Browsers without translate() support, e.g. IE7-8
            return 'margin';
        }
    };

    /**
     * Helpers
     */

    function clamp(n, min, max) {
        if (n < min) return min;
        if (n > max) return max;
        return n;
    }

    /**
     * (Internal) converts a percentage (`0..1`) to a bar translateX
     * percentage (`-100%..0%`).
     */

    function toBarPerc(n) {
        return (-1 + n) * 100;
    }


    /**
     * (Internal) returns the correct CSS for changing the bar's
     * position given an n percentage, and speed and ease from Settings
     */

    function barPositionCSS(n, speed, ease) {
        var barCSS;

        if (Settings.positionUsing === 'translate3d') {
            barCSS = { transform: 'translate3d('+toBarPerc(n)+'%,0,0)' };
        } else if (Settings.positionUsing === 'translate') {
            barCSS = { transform: 'translate('+toBarPerc(n)+'%,0)' };
        } else {
            barCSS = { 'margin-left': toBarPerc(n)+'%' };
        }

        barCSS.transition = 'all '+speed+'ms '+ease;

        return barCSS;
    }

    /**
     * (Internal) Queues a function to be executed.
     */

    var queue = (function() {
        var pending = [];

        function next() {
            var fn = pending.shift();
            if (fn) {
                fn(next);
            }
        }

        return function(fn) {
            pending.push(fn);
            if (pending.length == 1) next();
        };
    })();

    /**
     * (Internal) Applies css properties to an element, similar to the jQuery
     * css method.
     *
     * While this helper does assist with vendor prefixed property names, it
     * does not perform any manipulation of values prior to setting styles.
     */

    var css = (function() {
        var cssPrefixes = [ 'Webkit', 'O', 'Moz', 'ms' ],
            cssProps    = {};

        function camelCase(string) {
            return string.replace(/^-ms-/, 'ms-').replace(/-([\da-z])/gi, function(match, letter) {
                return letter.toUpperCase();
            });
        }

        function getVendorProp(name) {
            var style = document.body.style;
            if (name in style) return name;

            var i = cssPrefixes.length,
                capName = name.charAt(0).toUpperCase() + name.slice(1),
                vendorName;
            while (i--) {
                vendorName = cssPrefixes[i] + capName;
                if (vendorName in style) return vendorName;
            }

            return name;
        }

        function getStyleProp(name) {
            name = camelCase(name);
            return cssProps[name] || (cssProps[name] = getVendorProp(name));
        }

        function applyCss(element, prop, value) {
            prop = getStyleProp(prop);
            element.style[prop] = value;
        }

        return function(element, properties) {
            var args = arguments,
                prop,
                value;

            if (args.length == 2) {
                for (prop in properties) {
                    value = properties[prop];
                    if (value !== undefined && properties.hasOwnProperty(prop)) applyCss(element, prop, value);
                }
            } else {
                applyCss(element, args[1], args[2]);
            }
        }
    })();

    /**
     * (Internal) Determines if an element or space separated list of class names contains a class name.
     */

    function hasClass(element, name) {
        var list = typeof element == 'string' ? element : classList(element);
        return list.indexOf(' ' + name + ' ') >= 0;
    }

    /**
     * (Internal) Adds a class to an element.
     */

    function addClass(element, name) {
        var oldList = classList(element),
            newList = oldList + name;

        if (hasClass(oldList, name)) return;

        // Trim the opening space.
        element.className = newList.substring(1);
    }

    /**
     * (Internal) Removes a class from an element.
     */

    function removeClass(element, name) {
        var oldList = classList(element),
            newList;

        if (!hasClass(element, name)) return;

        // Replace the class name.
        newList = oldList.replace(' ' + name + ' ', ' ');

        // Trim the opening and closing spaces.
        element.className = newList.substring(1, newList.length - 1);
    }

    /**
     * (Internal) Gets a space separated list of the class names on the element.
     * The list is wrapped with a single space on each end to facilitate finding
     * matches within the list.
     */

    function classList(element) {
        return (' ' + (element && element.className || '') + ' ').replace(/\s+/gi, ' ');
    }

    /**
     * (Internal) Removes an element from the DOM.
     */

    function removeElement(element) {
        element && element.parentNode && element.parentNode.removeChild(element);
    }

    return NProgress;
});
