/**
 * Asyntai Chatbot Admin JavaScript
 *
 * @category  Asyntai
 * @package   AsyntaiSyliusChatbotPlugin
 * @author    Asyntai <hello@asyntai.com>
 * @copyright Copyright (c) Asyntai
 * @license   MIT License
 */

(function() {
    'use strict';

    var currentState = null;
    var config = window.asyntaiConfig || {};

    function showAlert(msg, ok) {
        var el = document.getElementById('asyntai-alert');
        if (!el) return;
        el.style.display = 'block';
        el.className = ok ? 'alert-success' : 'alert-error';
        el.textContent = msg;
    }

    function generateState() {
        return 'sylius_' + Math.random().toString(36).substr(2, 9);
    }

    function updateFallbackLink() {
        var fallbackLink = document.getElementById('asyntai-fallback-link');
        if (fallbackLink && currentState) {
            fallbackLink.href = 'https://asyntai.com/wp-auth?platform=sylius&state=' + encodeURIComponent(currentState);
        }
    }

    function showLoading(show) {
        var btn = document.getElementById('asyntai-connect-btn');
        if (btn) {
            if (show) {
                btn.disabled = true;
                btn.setAttribute('data-original-text', btn.textContent);
                btn.textContent = 'Connecting...';
            } else {
                btn.disabled = false;
                var originalText = btn.getAttribute('data-original-text');
                if (originalText) btn.textContent = originalText;
            }
        }
    }

    function openPopup() {
        currentState = generateState();
        updateFallbackLink();
        showLoading(true);
        showAlert('Waiting for authentication... Please complete the signup in the popup window.', true);

        var base = 'https://asyntai.com/wp-auth?platform=sylius';
        var url = base + '&state=' + encodeURIComponent(currentState);
        var w = 800, h = 720;
        var y = window.top.outerHeight / 2 + window.top.screenY - (h / 2);
        var x = window.top.outerWidth / 2 + window.top.screenX - (w / 2);
        var pop = window.open(url, 'asyntai_connect', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + w + ',height=' + h + ',top=' + y + ',left=' + x);

        setTimeout(function () {
            if (!pop || pop.closed || typeof pop.closed == 'undefined') {
                showAlert('Popup blocked. Please allow popups or use the link below.', false);
                showLoading(false);
                return;
            }
            pollForConnection(currentState);
        }, 100);
    }

    function pollForConnection(state) {
        var attempts = 0;
        function check() {
            if (attempts++ > 60) return;
            var script = document.createElement('script');
            var cb = 'asyntai_cb_' + Date.now();
            script.src = 'https://asyntai.com/connect-status.js?state=' + encodeURIComponent(state) + '&cb=' + cb;
            window[cb] = function (data) {
                try { delete window[cb]; } catch (e) { }
                if (data && data.site_id) {
                    saveConnection(data);
                    return;
                }
                setTimeout(check, 500);
            };
            script.onerror = function () {
                setTimeout(check, 1000);
            };
            document.head.appendChild(script);
        }
        setTimeout(check, 800);
    }

    function saveConnection(data) {
        showLoading(false);
        showAlert('Asyntai connected! Saving settings...', true);
        var payload = {
            site_id: data.site_id || ''
        };
        if (data.script_url) payload.script_url = data.script_url;
        if (data.account_email) payload.account_email = data.account_email;

        var saveUrl = config.saveUrl;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', saveUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var json;
                    try {
                        json = JSON.parse(xhr.responseText);
                    } catch (e) {
                        showAlert('Could not parse response', false);
                        return;
                    }

                    if (!json || !json.success) {
                        var errorMsg = 'Save failed';
                        if (json && json.error) {
                            errorMsg = json.error;
                        }
                        showAlert('Could not save settings: ' + errorMsg, false);
                        return;
                    }

                    showAlert('Asyntai connected. Chatbot enabled on all pages.', true);

                    // Update status
                    var statusEl = document.getElementById('asyntai-status');
                    if (statusEl) {
                        var statusHtml = 'Status: <span style="color:#28a745;font-weight:600;">Connected</span>';
                        if (payload.account_email) {
                            statusHtml += ' as ' + escapeHtml(payload.account_email);
                        }
                        statusHtml += ' <button type="button" id="asyntai-reset" class="ui button" style="margin-left:12px;">Reset</button>';
                        statusEl.innerHTML = statusHtml;

                        // Re-attach reset handler
                        var resetBtn = document.getElementById('asyntai-reset');
                        if (resetBtn) {
                            resetBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                resetConnection();
                            });
                        }
                    }

                    // Show connected box
                    var connectedBox = document.getElementById('asyntai-connected-box');
                    if (connectedBox) {
                        connectedBox.style.display = 'block';
                        if (!connectedBox.innerHTML.trim()) {
                            connectedBox.innerHTML = '<div style="padding:32px;border:1px solid #ddd;border-radius:8px;background:#fff;text-align:center;">' +
                                '<h2>Asyntai is now enabled</h2>' +
                                '<p style="font-size:16px;color:#666;">Set up your AI chatbot, review chat logs and more:</p>' +
                                '<a class="ui primary button" href="https://asyntai.com/dashboard" target="_blank" rel="noopener">Open Asyntai Panel</a>' +
                                '<p style="margin:20px 0 0;color:#666;"><strong>Tip:</strong> If you want to change how the AI answers, please <a href="https://asyntai.com/dashboard#setup" target="_blank" rel="noopener" style="color:#2563eb;text-decoration:underline;">go here</a>.</p>' +
                                '</div>';
                        }
                    }

                    // Hide popup wrap
                    var popupWrap = document.getElementById('asyntai-popup-wrap');
                    if (popupWrap) {
                        popupWrap.style.display = 'none';
                    }
                } else {
                    showAlert('Could not save settings: HTTP ' + xhr.status, false);
                }
            }
        };
        xhr.send(JSON.stringify(payload));
    }

    function resetConnection() {
        if (!confirm('Are you sure you want to reset the Asyntai connection?')) {
            return;
        }

        var resetUrl = config.resetUrl;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', resetUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var json;
                    try {
                        json = JSON.parse(xhr.responseText);
                    } catch (e) {
                        showAlert('Could not parse response', false);
                        return;
                    }

                    if (json && json.success) {
                        window.location.reload();
                    } else {
                        showAlert('Reset failed: ' + (json && json.error || 'Unknown error'), false);
                    }
                } else {
                    showAlert('Reset failed: HTTP ' + xhr.status, false);
                }
            }
        };
        xhr.send(JSON.stringify({ action: 'reset' }));
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function init() {
        // Initialize fallback link on page load
        currentState = generateState();
        updateFallbackLink();

        // Event handlers
        var connectBtn = document.getElementById('asyntai-connect-btn');
        if (connectBtn) {
            connectBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openPopup();
            });
        }

        var resetBtn = document.getElementById('asyntai-reset');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                resetConnection();
            });
        }

        var fallbackLink = document.getElementById('asyntai-fallback-link');
        if (fallbackLink) {
            fallbackLink.addEventListener('click', function() {
                // Re-generate state and update link when clicked
                currentState = generateState();
                updateFallbackLink();
                // Also start polling for this state
                setTimeout(function () {
                    pollForConnection(currentState);
                }, 1000);
            });
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
