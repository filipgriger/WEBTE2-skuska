$(document).ready(function () {
    $(".image-question").each(function () {
        var i = parseInt($(this).attr("id").substring(("question").length));
        if ($("#question" + i).length > 0 && $("#question" + i).hasClass("image-question")) {
            function createPaint(parent) {
                var canvas = elt('canvas', {
                    width: 600,
                    height: 400,
                    id: 'canvas' + i
                });

                var cx = canvas.getContext('2d');
                var toolbar = elt('div', {
                    class: 'toolbar'
                });

                for (var name in controls)
                    toolbar.appendChild(controls[name](cx));

                var panel = elt('div', {
                    class: 'picturepanel'
                }, canvas);
                parent.appendChild(elt('div', null, panel, toolbar));

                document.getElementById('delete' + i).addEventListener('click', function () {
                    cx.clearRect(0, 0, cx.canvas.width, cx.canvas.height);
                });

                checkExistingImage(cx.canvas.toDataURL(), i)
            }

            function elt(name, attributes) {
                var node = document.createElement(name);
                if (attributes) {
                    for (var attr in attributes)
                        if (attributes.hasOwnProperty(attr))
                            node.setAttribute(attr, attributes[attr]);
                }
                for (var i = 2; i < arguments.length; i++) {
                    var child = arguments[i];

                    if (typeof child == 'string')
                        child = document.createTextNode(child);
                    node.appendChild(child);
                }
                return node;
            }

            function relativePos(event, element) {
                var rect = element.getBoundingClientRect();
                return {
                    x: Math.floor(event.clientX - rect.left),
                    y: Math.floor(event.clientY - rect.top)
                };
            }

            function trackDrag(onMove, onEnd) {
                function end(event) {
                    removeEventListener('mousemove', onMove);
                    removeEventListener('mouseup', end);
                    if (onEnd)
                        onEnd(event);
                }

                addEventListener('mousemove', onMove);
                addEventListener('mouseup', end);
            }

            function loadImageURL(cx, url) {
                var image = document.createElement('img');
                image.addEventListener('load', function () {
                    var color = cx.fillStyle,
                        size = cx.lineWidth;
                    cx.canvas.width = image.width;
                    cx.canvas.height = image.height;
                    cx.drawImage(image, 0, 0);
                    cx.fillStyle = color;
                    cx.strokeStyle = color;
                    cx.lineWidth = size;
                });
                image.src = url;
            }

            var controls = Object.create(null);

            controls.tool = function (cx) {
                var select = elt('select', {class: 'form-control', id: 'select' + i});

                for (var name in tools)
                    select.appendChild(elt('option', {id: name}, name));

                select.addEventListener('change', function () {
                    if (this.value == "Line") {
                        document.querySelector("canvas").style.cursor = "crosshair";
                    } else if (this.value == "Erase") {
                        document.querySelector("canvas").style.cursor = "no-drop";
                    } else if (this.value == "Text") {
                        document.querySelector("canvas").style.cursor = "text";
                    }
                });

                cx.canvas.addEventListener('mousedown', function (event) {

                    if (event.which == 1) {

                        tools[select.value](event, cx);
                        event.preventDefault();
                    }
                });

                return elt('span', null, 'Nástroj: ', select);
            };

            controls.color = function (cx) {
                var input = elt('input', {
                    type: 'color',
                    class: 'form-control'
                });

                input.addEventListener('change', function () {
                    cx.fillStyle = input.value;
                    cx.strokeStyle = input.value;
                });
                return elt('span', null, 'Farba: ', input);
            };

            controls.brushSize = function (cx) {
                var select = elt('select', {class: 'form-control'});

                var sizes = [2, 3, 5, 8, 12];

                sizes.forEach(function (size) {
                    select.appendChild(elt('option', {
                        value: size
                    }, size + ' px'));
                });

                select.addEventListener('change', function () {
                    cx.lineWidth = select.value;
                });
                return elt('span', null, 'Veľkosť: ', select);
            };

            controls.save = function (cx) {
                var link = elt('a', {
                    rel: '#',
                    target: '_self'
                }, '');

                function update() {
                    try {
                        link.rel = cx.canvas.toDataURL();
                    } catch (e) {
                        if (e instanceof SecurityError) {
                            link.rel = 'javascript:alert(' +
                                JSON.stringify('Can\'t save: ' + e.toString()) + ')';
                        } else {
                            window.alert("Nope.");
                        }
                        throw e;
                    }
                }

                var saveButton = document.getElementById('save' + i);
                saveButton.addEventListener('mouseover', update);
                saveButton.addEventListener('focus', update);
                saveButton.addEventListener('click', function () {
                    saveImage(cx.canvas.toDataURL(), i);
                });

                link.addEventListener('mouseover', update);
                link.addEventListener('focus', update);
                link.addEventListener('click', function () {
                    saveImage(cx.canvas.toDataURL());
                });

                return link;
            };

            var tools = Object.create(null);

            tools.Line = function (event, cx, onEnd) {
                cx.lineCap = 'round';

                var pos = relativePos(event, cx.canvas);
                trackDrag(function (event) {
                    cx.beginPath();

                    cx.moveTo(pos.x, pos.y);

                    pos = relativePos(event, cx.canvas);

                    cx.lineTo(pos.x, pos.y);

                    cx.stroke();
                }, onEnd);
            };

            tools.Erase = function (event, cx) {

                cx.globalCompositeOperation = 'destination-out';
                tools.Line(event, cx, function () {
                    cx.globalCompositeOperation = 'source-over';
                });
            };

            tools.Text = function (event, cx) {
                var text = prompt('Text:', '');
                if (text) {
                    var pos = relativePos(event, cx.canvas);
                    cx.font = Math.max(7, cx.lineWidth) + 'px sans-serif';
                    cx.fillText(text, pos.x, pos.y);
                }
            }

            createPaint(document.querySelector('#paint-app' + i));

            var canvas = document.querySelector("#canvas" + i);
            var ctx = canvas.getContext('2d');

            function getTouchPos(e, element) {
                var rect = element.getBoundingClientRect();
                return {
                    x: Math.floor(e.touches[0].clientX - rect.left),
                    y: Math.floor(e.touches[0].clientY - rect.top)
                };
            }

            canvas.addEventListener('touchstart', function (e) {
                pos = getTouchPos(e, canvas);
                this.down = true;
                this.X = pos.x;
                this.Y = pos.y;
            });

            canvas.addEventListener('touchend', function () {
                this.down = false;
            });

            canvas.addEventListener('touchmove', function (e) {
                if (this.down) {
                    pos = getTouchPos(e, canvas);
                    with (ctx) {
                        if ($("#select" + i + " option:selected").val() == "Erase") {
                            ctx.globalCompositeOperation = 'destination-out';
                            tools.Line(e, ctx, function () {
                                ctx.globalCompositeOperation = 'source-over';
                            });
                        }
                        beginPath();
                        moveTo(this.X, this.Y);
                        lineTo(pos.x, pos.y);
                        stroke();
                    }
                    this.X = pos.x;
                    this.Y = pos.y;
                }
            });

            function saveImage(image, id) {
                //console.log(image)
                $.ajax({
                    url: './uploadImage.php',
                    method: 'POST',
                    data: {
                        image: image, id: id
                    },
                    success: function (data) {
                        console.log("Obrázok uložený pod názvom " + data);
                    }
                });
            }

            function checkExistingImage(image, id) {
                $.ajax({
                    url: './checkExistingImage.php',
                    method: 'POST',
                    data: {
                        image: image, id: id
                    },
                    success: function (data) {
                        if (data) {
                            loadImageURL(document.querySelector("#canvas" + i).getContext('2d'), data);
                        }
                    }
                });
            }

            var obj = document.getElementById("select" + i)
            for (var j = 0; j < obj.length; j++) {
                if (obj.options[j].value === "Line") {
                    obj.options[j].text = "Čiara";
                    obj.options[j].value = "Line";
                } else if (obj.options[j].value === "Erase") {
                    obj.options[j].text = "Guma";
                    obj.options[j].value = "Erase";
                }
            }
        }
    });
});
