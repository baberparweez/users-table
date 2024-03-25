/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/index.js":
/*!*************************!*\
  !*** ./assets/index.js ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./scss/style.scss */ \"./assets/scss/style.scss\");\n/* harmony import */ var _js_app_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/app.js */ \"./assets/js/app.js\");\n/* harmony import */ var _js_app_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_js_app_js__WEBPACK_IMPORTED_MODULE_1__);\n/**\n * SCSS imports\n */\n\n\n/**\n * JS imports\n */\n\n\n//# sourceURL=webpack://users-table/./assets/index.js?");

/***/ }),

/***/ "./assets/js/app.js":
/*!**************************!*\
  !*** ./assets/js/app.js ***!
  \**************************/
/***/ (() => {

eval("document.addEventListener(\"DOMContentLoaded\", function () {\n  var userLinks = document.querySelectorAll(\".users__table--user\");\n  var detailsContainer = document.querySelector(\".users__table--details\");\n  userLinks.forEach(function (link) {\n    link.addEventListener(\"click\", function (e) {\n      e.preventDefault();\n\n      // Display a loading message while fetching user details\n      detailsContainer.innerHTML = \"<svg width=\\\"40\\\" height=\\\"40\\\" viewBox=\\\"0 0 24 24\\\" xmlns=\\\"http://www.w3.org/2000/svg\\\"><style>.spinner_aj0A{transform-origin:center;animation:spinner_KYSC .75s infinite linear}@keyframes spinner_KYSC{100%{transform:rotate(360deg)}}</style><path d=\\\"M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z\\\" class=\\\"spinner_aj0A\\\"/></svg>\";\n      var userId = this.getAttribute(\"data-user-id\");\n      fetch(\"\".concat(myUsersTable.ajax_url, \"?action=fetch_user_details&user_id=\").concat(userId, \"&nonce=\").concat(myUsersTable.nonce), {\n        method: \"GET\",\n        credentials: \"same-origin\" // Needed for WordPress to accept the request\n      }).then(function (response) {\n        if (!response.ok) {\n          throw new Error(\"Network response was not ok\");\n        }\n        return response.json();\n      }).then(function (data) {\n        data = data.data;\n        // Update the DOM with user details\n        detailsContainer.innerHTML = \"\\n                        <div class=\\\"users__table--details_inner\\\">\\n                            <div>\\n                                <h3>\".concat(data.name, \"</h3>\\n                                <p>\").concat(data.phone, \"</p>\\n                                <p>\").concat(data.email, \"</p>\\n                                <p>\").concat(data.website, \"</p>\\n                            </div>\\n                            <div>\\n                                <h3>Address</h3>\\n                                <p>\").concat(data.address.city, \"</p>\\n                                <p>\").concat(data.address.street, \"</p>\\n                                <p>\").concat(data.address.suite, \"</p>\\n                                <p>\").concat(data.address.zipcode, \"</p>\\n                            </div>\\n                            <div>\\n                                <h3>Company</h3>\\n                                <p>\").concat(data.company.name, \"</p>\\n                                <p>\").concat(data.company.catchPhrase, \"</p>\\n                                <p>\").concat(data.company.bs, \"</p>\\n                            </div>\\n                        </div>\\n                    \");\n      })[\"catch\"](function (error) {\n        console.error(\"Error fetching user details:\", error);\n        // Display an error message to the user\n        detailsContainer.innerHTML = \"<p>Error loading user details.</p>\";\n      });\n    });\n  });\n});\n\n//# sourceURL=webpack://users-table/./assets/js/app.js?");

/***/ }),

/***/ "./assets/scss/style.scss":
/*!********************************!*\
  !*** ./assets/scss/style.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n\n\n//# sourceURL=webpack://users-table/./assets/scss/style.scss?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/index.js");
/******/ 	
/******/ })()
;