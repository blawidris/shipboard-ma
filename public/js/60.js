(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[60],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _utils_form__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/utils/form */ \"./resources/js/utils/form/index.js\");\n\n/* harmony default export */ __webpack_exports__[\"default\"] = ({\n  props: {\n    heading: {\n      type: String,\n      \"default\": null\n    },\n    subheading: {\n      type: String,\n      \"default\": null\n    }\n  },\n  data: function data() {\n    return {\n      form: new _utils_form__WEBPACK_IMPORTED_MODULE_0__[\"default\"]({\n        features_heading: this.heading,\n        features_subheading: this.subheading\n      })\n    };\n  },\n  methods: {\n    submit: function submit() {\n      var _this = this;\n      this.form.processing = true;\n      this.$inertia.put(route('admin:landing-page-features-settings.update'), this.form.data(), {\n        preserveState: false\n      }).then(function () {\n        return _this.form.processing = false;\n      });\n    }\n  }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vcmVzb3VyY2VzL2pzL3ZpZXdzL2JhY2svYWRtaW4vc2V0dGluZ3MvbGFuZGluZy1wYWdlL2ZlYXR1cmVzLXNldHRpbmdzLnZ1ZT8zMjExIl0sIm5hbWVzIjpbInByb3BzIiwiaGVhZGluZyIsInR5cGUiLCJTdHJpbmciLCJzdWJoZWFkaW5nIiwiZGF0YSIsImZvcm0iLCJGb3JtIiwiZmVhdHVyZXNfaGVhZGluZyIsImZlYXR1cmVzX3N1YmhlYWRpbmciLCJtZXRob2RzIiwic3VibWl0IiwiX3RoaXMiLCJwcm9jZXNzaW5nIiwiJGluZXJ0aWEiLCJwdXQiLCJyb3V0ZSIsInByZXNlcnZlU3RhdGUiLCJ0aGVuIl0sIm1hcHBpbmdzIjoiQUErQkE7QUFBQTtBQUFBO0FBRUE7RUFDQUEsS0FBQTtJQUNBQyxPQUFBO01BQ0FDLElBQUEsRUFBQUMsTUFBQTtNQUNBO0lBQ0E7SUFDQUMsVUFBQTtNQUNBRixJQUFBLEVBQUFDLE1BQUE7TUFDQTtJQUNBO0VBQ0E7RUFFQUUsSUFBQSxXQUFBQSxLQUFBO0lBQ0E7TUFDQUMsSUFBQSxNQUFBQyxtREFBQTtRQUNBQyxnQkFBQSxPQUFBUCxPQUFBO1FBQ0FRLG1CQUFBLE9BQUFMO01BQ0E7SUFDQTtFQUNBO0VBRUFNLE9BQUE7SUFDQUMsTUFBQSxXQUFBQSxPQUFBO01BQUEsSUFBQUMsS0FBQTtNQUNBLEtBQUFOLElBQUEsQ0FBQU8sVUFBQTtNQUVBLEtBQUFDLFFBQUEsQ0FBQUMsR0FBQSxDQUFBQyxLQUFBLHNEQUFBVixJQUFBLENBQUFELElBQUE7UUFBQVksYUFBQTtNQUFBLEdBQ0FDLElBQUE7UUFBQSxPQUFBTixLQUFBLENBQUFOLElBQUEsQ0FBQU8sVUFBQTtNQUFBO0lBQ0E7RUFDQTtBQUNBIiwiZmlsZSI6Ii4vbm9kZV9tb2R1bGVzL2JhYmVsLWxvYWRlci9saWIvaW5kZXguanM/IS4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2luZGV4LmpzPyEuL3Jlc291cmNlcy9qcy92aWV3cy9iYWNrL2FkbWluL3NldHRpbmdzL2xhbmRpbmctcGFnZS9mZWF0dXJlcy1zZXR0aW5ncy52dWU/dnVlJnR5cGU9c2NyaXB0Jmxhbmc9anMmLmpzIiwic291cmNlc0NvbnRlbnQiOlsiPHRlbXBsYXRlPlxyXG4gICAgPGRpdiBjbGFzcz1cImZsZXggZmxleC1jb2wgYmctd2hpdGUgcm91bmRlZC1sZyBzaGFkb3cgbWItNlwiPlxyXG4gICAgICAgIDxkaXYgY2xhc3M9XCJweC00IHB5LTUgc206cC02XCI+XHJcbiAgICAgICAgICAgIDxoMyBjbGFzcz1cInRleHQtbGcgbGVhZGluZy02IGZvbnQtbWVkaXVtIHRleHQtZ3JheS05MDBcIj5cclxuICAgICAgICAgICAgICAgIHt7ICR0cmFucygnaGVhZGluZ3MuZmVhdHVyZXMtc2VjdGlvbicpIH19XHJcbiAgICAgICAgICAgIDwvaDM+XHJcbiAgICAgICAgPC9kaXY+XHJcblxyXG4gICAgICAgIDxkaXYgY2xhc3M9XCJweC00IHB5LTUgc206cC02XCI+XHJcbiAgICAgICAgICAgIDxkaXYgY2xhc3M9XCJmb3JtLWdyb3VwIG1iLTZcIj5cclxuICAgICAgICAgICAgICAgIDxsYWJlbCBjbGFzcz1cImZvcm0tbGFiZWxcIj57eyAkdHJhbnMoJ2xhYmVscy5mZWF0dXJlcy1oZWFkaW5nJykgfX08L2xhYmVsPlxyXG4gICAgICAgICAgICAgICAgPGlucHV0IGNsYXNzPVwiZm9ybS1pbnB1dFwiIHYtbW9kZWw9XCJmb3JtLmZlYXR1cmVzX2hlYWRpbmdcIj5cclxuICAgICAgICAgICAgICAgIDxzcGFuIGNsYXNzPVwiaW52YWxpZC1mZWVkYmFja1wiIHYtaWY9XCIkcGFnZS5lcnJvcnMuaGFzKCdmZWF0dXJlc19oZWFkaW5nJylcIj57eyAkcGFnZS5lcnJvcnMuZmlyc3QoJ2ZlYXR1cmVzX2hlYWRpbmcnKSB9fTwvc3Bhbj5cclxuICAgICAgICAgICAgPC9kaXY+XHJcblxyXG4gICAgICAgICAgICA8ZGl2IGNsYXNzPVwiZm9ybS1ncm91cCBtYi02XCI+XHJcbiAgICAgICAgICAgICAgICA8bGFiZWwgY2xhc3M9XCJmb3JtLWxhYmVsXCI+e3sgJHRyYW5zKCdsYWJlbHMuZmVhdHVyZXMtc3ViLWhlYWRpbmcnKSB9fTwvbGFiZWw+XHJcbiAgICAgICAgICAgICAgICA8aW5wdXQgY2xhc3M9XCJmb3JtLWlucHV0XCIgdi1tb2RlbD1cImZvcm0uZmVhdHVyZXNfc3ViaGVhZGluZ1wiPlxyXG4gICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9XCJpbnZhbGlkLWZlZWRiYWNrXCIgdi1pZj1cIiRwYWdlLmVycm9ycy5oYXMoJ2ZlYXR1cmVzX3N1YmhlYWRpbmcnKVwiPnt7ICRwYWdlLmVycm9ycy5maXJzdCgnZmVhdHVyZXNfc3ViaGVhZGluZycpIH19PC9zcGFuPlxyXG4gICAgICAgICAgICA8L2Rpdj5cclxuICAgICAgICA8L2Rpdj5cclxuXHJcbiAgICAgICAgPGRpdiBjbGFzcz1cImJnLWdyYXktNTAgdGV4dC1yaWdodCByb3VuZGVkLWItbGcgcHgtNCBweS01IHNtOnAtNlwiPlxyXG4gICAgICAgICAgICA8YnV0dG9uIEBjbGljaz1cInN1Ym1pdCgpXCIgdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1pbmRpZ29cIiA6ZGlzYWJsZWQ9XCJmb3JtLnByb2Nlc3NpbmdcIj5cclxuICAgICAgICAgICAgICAgIHt7ICR0cmFucygnbGFiZWxzLnNhdmUtc2V0dGluZ3MnKSB9fVxyXG4gICAgICAgICAgICA8L2J1dHRvbj5cclxuICAgICAgICA8L2Rpdj5cclxuICAgIDwvZGl2PlxyXG48L3RlbXBsYXRlPlxyXG5cclxuPHNjcmlwdD5cclxuICAgIGltcG9ydCBGb3JtIGZyb20gJ0AvdXRpbHMvZm9ybSdcclxuXHJcbiAgICBleHBvcnQgZGVmYXVsdCB7XHJcbiAgICAgICAgcHJvcHM6IHtcclxuICAgICAgICAgICAgaGVhZGluZzoge1xyXG4gICAgICAgICAgICAgICAgdHlwZTogU3RyaW5nLFxyXG4gICAgICAgICAgICAgICAgZGVmYXVsdDogbnVsbFxyXG4gICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICBzdWJoZWFkaW5nOiB7XHJcbiAgICAgICAgICAgICAgICB0eXBlOiBTdHJpbmcsXHJcbiAgICAgICAgICAgICAgICBkZWZhdWx0OiBudWxsXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICBkYXRhKCkge1xyXG4gICAgICAgICAgICByZXR1cm4ge1xyXG4gICAgICAgICAgICAgICAgZm9ybTogbmV3IEZvcm0oe1xyXG4gICAgICAgICAgICAgICAgICAgIGZlYXR1cmVzX2hlYWRpbmc6IHRoaXMuaGVhZGluZyxcclxuICAgICAgICAgICAgICAgICAgICBmZWF0dXJlc19zdWJoZWFkaW5nOiB0aGlzLnN1YmhlYWRpbmdcclxuICAgICAgICAgICAgICAgIH0pXHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9LFxyXG5cclxuICAgICAgICBtZXRob2RzOiB7XHJcbiAgICAgICAgICAgIHN1Ym1pdCgpIHtcclxuICAgICAgICAgICAgICAgIHRoaXMuZm9ybS5wcm9jZXNzaW5nID0gdHJ1ZTtcclxuXHJcbiAgICAgICAgICAgICAgICB0aGlzLiRpbmVydGlhLnB1dChyb3V0ZSgnYWRtaW46bGFuZGluZy1wYWdlLWZlYXR1cmVzLXNldHRpbmdzLnVwZGF0ZScpLCB0aGlzLmZvcm0uZGF0YSgpLCB7cHJlc2VydmVTdGF0ZTogZmFsc2V9KVxyXG4gICAgICAgICAgICAgICAgICAgIC50aGVuKCgpID0+IHRoaXMuZm9ybS5wcm9jZXNzaW5nID0gZmFsc2UpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG48L3NjcmlwdD5cclxuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js&\n");

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec&":
/*!******************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return render; });\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return staticRenderFns; });\nvar render = function render() {\n  var _vm = this,\n    _c = _vm._self._c;\n  return _c(\"div\", {\n    staticClass: \"flex flex-col bg-white rounded-lg shadow mb-6\"\n  }, [_c(\"div\", {\n    staticClass: \"px-4 py-5 sm:p-6\"\n  }, [_c(\"h3\", {\n    staticClass: \"text-lg leading-6 font-medium text-gray-900\"\n  }, [_vm._v(\"\\n            \" + _vm._s(_vm.$trans(\"headings.features-section\")) + \"\\n        \")])]), _vm._v(\" \"), _c(\"div\", {\n    staticClass: \"px-4 py-5 sm:p-6\"\n  }, [_c(\"div\", {\n    staticClass: \"form-group mb-6\"\n  }, [_c(\"label\", {\n    staticClass: \"form-label\"\n  }, [_vm._v(_vm._s(_vm.$trans(\"labels.features-heading\")))]), _vm._v(\" \"), _c(\"input\", {\n    directives: [{\n      name: \"model\",\n      rawName: \"v-model\",\n      value: _vm.form.features_heading,\n      expression: \"form.features_heading\"\n    }],\n    staticClass: \"form-input\",\n    domProps: {\n      value: _vm.form.features_heading\n    },\n    on: {\n      input: function input($event) {\n        if ($event.target.composing) return;\n        _vm.$set(_vm.form, \"features_heading\", $event.target.value);\n      }\n    }\n  }), _vm._v(\" \"), _vm.$page.errors.has(\"features_heading\") ? _c(\"span\", {\n    staticClass: \"invalid-feedback\"\n  }, [_vm._v(_vm._s(_vm.$page.errors.first(\"features_heading\")))]) : _vm._e()]), _vm._v(\" \"), _c(\"div\", {\n    staticClass: \"form-group mb-6\"\n  }, [_c(\"label\", {\n    staticClass: \"form-label\"\n  }, [_vm._v(_vm._s(_vm.$trans(\"labels.features-sub-heading\")))]), _vm._v(\" \"), _c(\"input\", {\n    directives: [{\n      name: \"model\",\n      rawName: \"v-model\",\n      value: _vm.form.features_subheading,\n      expression: \"form.features_subheading\"\n    }],\n    staticClass: \"form-input\",\n    domProps: {\n      value: _vm.form.features_subheading\n    },\n    on: {\n      input: function input($event) {\n        if ($event.target.composing) return;\n        _vm.$set(_vm.form, \"features_subheading\", $event.target.value);\n      }\n    }\n  }), _vm._v(\" \"), _vm.$page.errors.has(\"features_subheading\") ? _c(\"span\", {\n    staticClass: \"invalid-feedback\"\n  }, [_vm._v(_vm._s(_vm.$page.errors.first(\"features_subheading\")))]) : _vm._e()])]), _vm._v(\" \"), _c(\"div\", {\n    staticClass: \"bg-gray-50 text-right rounded-b-lg px-4 py-5 sm:p-6\"\n  }, [_c(\"button\", {\n    staticClass: \"btn btn-indigo\",\n    attrs: {\n      type: \"button\",\n      disabled: _vm.form.processing\n    },\n    on: {\n      click: function click($event) {\n        return _vm.submit();\n      }\n    }\n  }, [_vm._v(\"\\n            \" + _vm._s(_vm.$trans(\"labels.save-settings\")) + \"\\n        \")])])]);\n};\nvar staticRenderFns = [];\nrender._withStripped = true;\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdmlld3MvYmFjay9hZG1pbi9zZXR0aW5ncy9sYW5kaW5nLXBhZ2UvZmVhdHVyZXMtc2V0dGluZ3MudnVlPzc5N2MiXSwibmFtZXMiOlsicmVuZGVyIiwiX3ZtIiwiX2MiLCJfc2VsZiIsInN0YXRpY0NsYXNzIiwiX3YiLCJfcyIsIiR0cmFucyIsImRpcmVjdGl2ZXMiLCJuYW1lIiwicmF3TmFtZSIsInZhbHVlIiwiZm9ybSIsImZlYXR1cmVzX2hlYWRpbmciLCJleHByZXNzaW9uIiwiZG9tUHJvcHMiLCJvbiIsImlucHV0IiwiJGV2ZW50IiwidGFyZ2V0IiwiY29tcG9zaW5nIiwiJHNldCIsIiRwYWdlIiwiZXJyb3JzIiwiaGFzIiwiZmlyc3QiLCJfZSIsImZlYXR1cmVzX3N1YmhlYWRpbmciLCJhdHRycyIsInR5cGUiLCJkaXNhYmxlZCIsInByb2Nlc3NpbmciLCJjbGljayIsInN1Ym1pdCIsInN0YXRpY1JlbmRlckZucyIsIl93aXRoU3RyaXBwZWQiXSwibWFwcGluZ3MiOiJBQUFBO0FBQUE7QUFBQTtBQUFBLElBQUlBLE1BQU0sR0FBRyxTQUFTQSxNQUFNQSxDQUFBLEVBQUc7RUFDN0IsSUFBSUMsR0FBRyxHQUFHLElBQUk7SUFDWkMsRUFBRSxHQUFHRCxHQUFHLENBQUNFLEtBQUssQ0FBQ0QsRUFBRTtFQUNuQixPQUFPQSxFQUFFLENBQ1AsS0FBSyxFQUNMO0lBQUVFLFdBQVcsRUFBRTtFQUFnRCxDQUFDLEVBQ2hFLENBQ0VGLEVBQUUsQ0FBQyxLQUFLLEVBQUU7SUFBRUUsV0FBVyxFQUFFO0VBQW1CLENBQUMsRUFBRSxDQUM3Q0YsRUFBRSxDQUNBLElBQUksRUFDSjtJQUFFRSxXQUFXLEVBQUU7RUFBOEMsQ0FBQyxFQUM5RCxDQUNFSCxHQUFHLENBQUNJLEVBQUUsQ0FDSixnQkFBZ0IsR0FDZEosR0FBRyxDQUFDSyxFQUFFLENBQUNMLEdBQUcsQ0FBQ00sTUFBTSxDQUFDLDJCQUEyQixDQUFDLENBQUMs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SixnQkFBZ0IsR0FDZEosR0FBRyxDQUFDSyxFQUFFLENBQUNMLEdBQUcsQ0FBQ00sTUFBTSxDQUFDLHNCQUFzQixDQUFDLENBQUMsR0FDMUMsWUFDSixDQUFDLENBRUwsQ0FBQyxDQUVMLENBQUMsQ0FFTCxDQUFDO0FBQ0gsQ0FBQztBQUNELElBQUkyQixlQUFlLEdBQUcsRUFBRTtBQUN4QmxDLE1BQU0sQ0FBQ21DLGFBQWEsR0FBRyxJQUFJIiwiZmlsZSI6Ii4vbm9kZV9tb2R1bGVzL2JhYmVsLWxvYWRlci9saWIvaW5kZXguanM/IS4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2xvYWRlcnMvdGVtcGxhdGVMb2FkZXIuanM/IS4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2luZGV4LmpzPyEuL3Jlc291cmNlcy9qcy92aWV3cy9iYWNrL2FkbWluL3NldHRpbmdzL2xhbmRpbmctcGFnZS9mZWF0dXJlcy1zZXR0aW5ncy52dWU/dnVlJnR5cGU9dGVtcGxhdGUmaWQ9MjkxYjhhZWMmLmpzIiwic291cmNlc0NvbnRlbnQiOlsidmFyIHJlbmRlciA9IGZ1bmN0aW9uIHJlbmRlcigpIHtcbiAgdmFyIF92bSA9IHRoaXMsXG4gICAgX2MgPSBfdm0uX3NlbGYuX2NcbiAgcmV0dXJuIF9jKFxuICAgIFwiZGl2XCIsXG4gICAgeyBzdGF0aWNDbGFzczogXCJmbGV4IGZsZXgtY29sIGJnLXdoaXRlIHJvdW5kZWQtbGcgc2hhZG93IG1iLTZcIiB9LFxuICAgIFtcbiAgICAgIF9jKFwiZGl2XCIsIHsgc3RhdGljQ2xhc3M6IFwicHgtNCBweS01IHNtOnAtNlwiIH0sIFtcbiAgICAgICAgX2MoXG4gICAgICAgICAgXCJoM1wiLFxuICAgICAgICAgIHsgc3RhdGljQ2xhc3M6IFwidGV4dC1sZyBsZWFkaW5nLTYgZm9udC1tZWRpdW0gdGV4dC1ncmF5LTkwMFwiIH0sXG4gICAgICAgICAgW1xuICAgICAgICAgICAgX3ZtLl92KFxuICAgICAgICAgICAgICBcIlxcbiAgICAgICAgICAgIFwiICtcbiAgICAgICAgICAgICAgICBfdm0uX3MoX3ZtLiR0cmFucyhcImhlYWRpbmdzLmZlYXR1cmVzLXNlY3Rpb25cIikpICtcbiAgICAgICAgICAgICAgICBcIlxcbiAgICAgICAgXCJcbiAgICAgICAgICAgICksXG4gICAgICAgICAgXVxuICAgICAgICApLFxuICAgICAgXSksXG4gICAgICBfdm0uX3YoXCIgXCIpLFxuICAgICAgX2MoXCJkaXZcIiwgeyBzdGF0aWNDbGFzczogXCJweC00IHB5LTUgc206cC02XCIgfSwgW1xuICAgICAgICBfYyhcImRpdlwiLCB7IHN0YXRpY0NsYXNzOiBcImZvcm0tZ3JvdXAgbWItNlwiIH0sIFtcbiAgICAgICAgICBfYyhcImxhYmVsXCIsIHsgc3RhdGljQ2xhc3M6IFwiZm9ybS1sYWJlbFwiIH0sIFtcbiAgICAgICAgICAgIF92bS5fdihfdm0uX3MoX3ZtLiR0cmFucyhcImxhYmVscy5mZWF0dXJlcy1oZWFkaW5nXCIpKSksXG4gICAgICAgICAgXSksXG4gICAgICAgICAgX3ZtLl92KFwiIFwiKSxcbiAgICAgICAgICBfYyhcImlucHV0XCIsIHtcbiAgICAgICAgICAgIGRpcmVjdGl2ZXM6IFtcbiAgICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgIG5hbWU6IFwibW9kZWxcIixcbiAgICAgICAgICAgICAgICByYXdOYW1lOiBcInYtbW9kZWxcIixcbiAgICAgICAgICAgICAgICB2YWx1ZTogX3ZtLmZvcm0uZmVhdHVyZXNfaGVhZGluZyxcbiAgICAgICAgICAgICAgICBleHByZXNzaW9uOiBcImZvcm0uZmVhdHVyZXNfaGVhZGluZ1wiLFxuICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgXSxcbiAgICAgICAgICAgIHN0YXRpY0NsYXNzOiBcImZvcm0taW5wdXRcIixcbiAgICAgICAgICAgIGRvbVByb3BzOiB7IHZhbHVlOiBfdm0uZm9ybS5mZWF0dXJlc19oZWFkaW5nIH0sXG4gICAgICAgICAgICBvbjoge1xuICAgICAgICAgICAgICBpbnB1dDogZnVuY3Rpb24gKCRldmVudCkge1xuICAgICAgICAgICAgICAgIGlmICgkZXZlbnQudGFyZ2V0LmNvbXBvc2luZykgcmV0dXJuXG4gICAgICAgICAgICAgICAgX3ZtLiRzZXQoX3ZtLmZvcm0sIFwiZmVhdHVyZXNfaGVhZGluZ1wiLCAkZXZlbnQudGFyZ2V0LnZhbHVlKVxuICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICB9KSxcbiAgICAgICAgICBfdm0uX3YoXCIgXCIpLFxuICAgICAgICAgIF92bS4kcGFnZS5lcnJvcnMuaGFzKFwiZmVhdHVyZXNfaGVhZGluZ1wiKVxuICAgICAgICAgICAgPyBfYyhcInNwYW5cIiwgeyBzdGF0aWNDbGFzczogXCJpbnZhbGlkLWZlZWRiYWNrXCIgfSwgW1xuICAgICAgICAgICAgICAgIF92bS5fdihfdm0uX3MoX3ZtLiRwYWdlLmVycm9ycy5maXJzdChcImZlYXR1cmVzX2hlYWRpbmdcIikpKSxcbiAgICAgICAgICAgICAgXSlcbiAgICAgICAgICAgIDogX3ZtLl9lKCksXG4gICAgICAgIF0pLFxuICAgICAgICBfdm0uX3YoXCIgXCIpLFxuICAgICAgICBfYyhcImRpdlwiLCB7IHN0YXRpY0NsYXNzOiBcImZvcm0tZ3JvdXAgbWItNlwiIH0sIFtcbiAgICAgICAgICBfYyhcImxhYmVsXCIsIHsgc3RhdGljQ2xhc3M6IFwiZm9ybS1sYWJlbFwiIH0sIFtcbiAgICAgICAgICAgIF92bS5fdihfdm0uX3MoX3ZtLiR0cmFucyhcImxhYmVscy5mZWF0dXJlcy1zdWItaGVhZGluZ1wiKSkpLFxuICAgICAgICAgIF0pLFxuICAgICAgICAgIF92bS5fdihcIiBcIiksXG4gICAgICAgICAgX2MoXCJpbnB1dFwiLCB7XG4gICAgICAgICAgICBkaXJlY3RpdmVzOiBbXG4gICAgICAgICAgICAgIHtcbiAgICAgICAgICAgICAgICBuYW1lOiBcIm1vZGVsXCIsXG4gICAgICAgICAgICAgICAgcmF3TmFtZTogXCJ2LW1vZGVsXCIsXG4gICAgICAgICAgICAgICAgdmFsdWU6IF92bS5mb3JtLmZlYXR1cmVzX3N1YmhlYWRpbmcsXG4gICAgICAgICAgICAgICAgZXhwcmVzc2lvbjogXCJmb3JtLmZlYXR1cmVzX3N1YmhlYWRpbmdcIixcbiAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIF0sXG4gICAgICAgICAgICBzdGF0aWNDbGFzczogXCJmb3JtLWlucHV0XCIsXG4gICAgICAgICAgICBkb21Qcm9wczogeyB2YWx1ZTogX3ZtLmZvcm0uZmVhdHVyZXNfc3ViaGVhZGluZyB9LFxuICAgICAgICAgICAgb246IHtcbiAgICAgICAgICAgICAgaW5wdXQ6IGZ1bmN0aW9uICgkZXZlbnQpIHtcbiAgICAgICAgICAgICAgICBpZiAoJGV2ZW50LnRhcmdldC5jb21wb3NpbmcpIHJldHVyblxuICAgICAgICAgICAgICAgIF92bS4kc2V0KF92bS5mb3JtLCBcImZlYXR1cmVzX3N1YmhlYWRpbmdcIiwgJGV2ZW50LnRhcmdldC52YWx1ZSlcbiAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgfSksXG4gICAgICAgICAgX3ZtLl92KFwiIFwiKSxcbiAgICAgICAgICBfdm0uJHBhZ2UuZXJyb3JzLmhhcyhcImZlYXR1cmVzX3N1YmhlYWRpbmdcIilcbiAgICAgICAgICAgID8gX2MoXCJzcGFuXCIsIHsgc3RhdGljQ2xhc3M6IFwiaW52YWxpZC1mZWVkYmFja1wiIH0sIFtcbiAgICAgICAgICAgICAgICBfdm0uX3YoX3ZtLl9zKF92bS4kcGFnZS5lcnJvcnMuZmlyc3QoXCJmZWF0dXJlc19zdWJoZWFkaW5nXCIpKSksXG4gICAgICAgICAgICAgIF0pXG4gICAgICAgICAgICA6IF92bS5fZSgpLFxuICAgICAgICBdKSxcbiAgICAgIF0pLFxuICAgICAgX3ZtLl92KFwiIFwiKSxcbiAgICAgIF9jKFxuICAgICAgICBcImRpdlwiLFxuICAgICAgICB7IHN0YXRpY0NsYXNzOiBcImJnLWdyYXktNTAgdGV4dC1yaWdodCByb3VuZGVkLWItbGcgcHgtNCBweS01IHNtOnAtNlwiIH0sXG4gICAgICAgIFtcbiAgICAgICAgICBfYyhcbiAgICAgICAgICAgIFwiYnV0dG9uXCIsXG4gICAgICAgICAgICB7XG4gICAgICAgICAgICAgIHN0YXRpY0NsYXNzOiBcImJ0biBidG4taW5kaWdvXCIsXG4gICAgICAgICAgICAgIGF0dHJzOiB7IHR5cGU6IFwiYnV0dG9uXCIsIGRpc2FibGVkOiBfdm0uZm9ybS5wcm9jZXNzaW5nIH0sXG4gICAgICAgICAgICAgIG9uOiB7XG4gICAgICAgICAgICAgICAgY2xpY2s6IGZ1bmN0aW9uICgkZXZlbnQpIHtcbiAgICAgICAgICAgICAgICAgIHJldHVybiBfdm0uc3VibWl0KClcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIFtcbiAgICAgICAgICAgICAgX3ZtLl92KFxuICAgICAgICAgICAgICAgIFwiXFxuICAgICAgICAgICAgXCIgK1xuICAgICAgICAgICAgICAgICAgX3ZtLl9zKF92bS4kdHJhbnMoXCJsYWJlbHMuc2F2ZS1zZXR0aW5nc1wiKSkgK1xuICAgICAgICAgICAgICAgICAgXCJcXG4gICAgICAgIFwiXG4gICAgICAgICAgICAgICksXG4gICAgICAgICAgICBdXG4gICAgICAgICAgKSxcbiAgICAgICAgXVxuICAgICAgKSxcbiAgICBdXG4gIClcbn1cbnZhciBzdGF0aWNSZW5kZXJGbnMgPSBbXVxucmVuZGVyLl93aXRoU3RyaXBwZWQgPSB0cnVlXG5cbmV4cG9ydCB7IHJlbmRlciwgc3RhdGljUmVuZGVyRm5zIH0iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec&\n");

/***/ }),

/***/ "./resources/js/views/back/admin/settings/landing-page/features-settings.vue":
/*!***********************************************************************************!*\
  !*** ./resources/js/views/back/admin/settings/landing-page/features-settings.vue ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _features_settings_vue_vue_type_template_id_291b8aec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./features-settings.vue?vue&type=template&id=291b8aec& */ \"./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec&\");\n/* harmony import */ var _features_settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./features-settings.vue?vue&type=script&lang=js& */ \"./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ \"./node_modules/vue-loader/lib/runtime/componentNormalizer.js\");\n\n\n\n\n\n/* normalize component */\n\nvar component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__[\"default\"])(\n  _features_settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  _features_settings_vue_vue_type_template_id_291b8aec___WEBPACK_IMPORTED_MODULE_0__[\"render\"],\n  _features_settings_vue_vue_type_template_id_291b8aec___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"],\n  false,\n  null,\n  null,\n  null\n  \n)\n\n/* hot reload */\nif (false) { var api; }\ncomponent.options.__file = \"resources/js/views/back/admin/settings/landing-page/features-settings.vue\"\n/* harmony default export */ __webpack_exports__[\"default\"] = (component.exports);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdmlld3MvYmFjay9hZG1pbi9zZXR0aW5ncy9sYW5kaW5nLXBhZ2UvZmVhdHVyZXMtc2V0dGluZ3MudnVlP2EzNmMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFBZ0c7QUFDM0I7QUFDTDs7O0FBR2hFO0FBQ3lHO0FBQ3pHLGdCQUFnQiwyR0FBVTtBQUMxQixFQUFFLHVGQUFNO0FBQ1IsRUFBRSw0RkFBTTtBQUNSLEVBQUUscUdBQWU7QUFDakI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQSxJQUFJLEtBQVUsRUFBRSxZQWlCZjtBQUNEO0FBQ2UsZ0YiLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvdmlld3MvYmFjay9hZG1pbi9zZXR0aW5ncy9sYW5kaW5nLXBhZ2UvZmVhdHVyZXMtc2V0dGluZ3MudnVlLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHsgcmVuZGVyLCBzdGF0aWNSZW5kZXJGbnMgfSBmcm9tIFwiLi9mZWF0dXJlcy1zZXR0aW5ncy52dWU/dnVlJnR5cGU9dGVtcGxhdGUmaWQ9MjkxYjhhZWMmXCJcbmltcG9ydCBzY3JpcHQgZnJvbSBcIi4vZmVhdHVyZXMtc2V0dGluZ3MudnVlP3Z1ZSZ0eXBlPXNjcmlwdCZsYW5nPWpzJlwiXG5leHBvcnQgKiBmcm9tIFwiLi9mZWF0dXJlcy1zZXR0aW5ncy52dWU/dnVlJnR5cGU9c2NyaXB0Jmxhbmc9anMmXCJcblxuXG4vKiBub3JtYWxpemUgY29tcG9uZW50ICovXG5pbXBvcnQgbm9ybWFsaXplciBmcm9tIFwiIS4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy92dWUtbG9hZGVyL2xpYi9ydW50aW1lL2NvbXBvbmVudE5vcm1hbGl6ZXIuanNcIlxudmFyIGNvbXBvbmVudCA9IG5vcm1hbGl6ZXIoXG4gIHNjcmlwdCxcbiAgcmVuZGVyLFxuICBzdGF0aWNSZW5kZXJGbnMsXG4gIGZhbHNlLFxuICBudWxsLFxuICBudWxsLFxuICBudWxsXG4gIFxuKVxuXG4vKiBob3QgcmVsb2FkICovXG5pZiAobW9kdWxlLmhvdCkge1xuICB2YXIgYXBpID0gcmVxdWlyZShcIkM6XFxcXHhhbXBwXFxcXGh0ZG9jc1xcXFxzaGlwYm9hcmRcXFxcbm9kZV9tb2R1bGVzXFxcXHZ1ZS1ob3QtcmVsb2FkLWFwaVxcXFxkaXN0XFxcXGluZGV4LmpzXCIpXG4gIGFwaS5pbnN0YWxsKHJlcXVpcmUoJ3Z1ZScpKVxuICBpZiAoYXBpLmNvbXBhdGlibGUpIHtcbiAgICBtb2R1bGUuaG90LmFjY2VwdCgpXG4gICAgaWYgKCFhcGkuaXNSZWNvcmRlZCgnMjkxYjhhZWMnKSkge1xuICAgICAgYXBpLmNyZWF0ZVJlY29yZCgnMjkxYjhhZWMnLCBjb21wb25lbnQub3B0aW9ucylcbiAgICB9IGVsc2Uge1xuICAgICAgYXBpLnJlbG9hZCgnMjkxYjhhZWMnLCBjb21wb25lbnQub3B0aW9ucylcbiAgICB9XG4gICAgbW9kdWxlLmhvdC5hY2NlcHQoXCIuL2ZlYXR1cmVzLXNldHRpbmdzLnZ1ZT92dWUmdHlwZT10ZW1wbGF0ZSZpZD0yOTFiOGFlYyZcIiwgZnVuY3Rpb24gKCkge1xuICAgICAgYXBpLnJlcmVuZGVyKCcyOTFiOGFlYycsIHtcbiAgICAgICAgcmVuZGVyOiByZW5kZXIsXG4gICAgICAgIHN0YXRpY1JlbmRlckZuczogc3RhdGljUmVuZGVyRm5zXG4gICAgICB9KVxuICAgIH0pXG4gIH1cbn1cbmNvbXBvbmVudC5vcHRpb25zLl9fZmlsZSA9IFwicmVzb3VyY2VzL2pzL3ZpZXdzL2JhY2svYWRtaW4vc2V0dGluZ3MvbGFuZGluZy1wYWdlL2ZlYXR1cmVzLXNldHRpbmdzLnZ1ZVwiXG5leHBvcnQgZGVmYXVsdCBjb21wb25lbnQuZXhwb3J0cyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/views/back/admin/settings/landing-page/features-settings.vue\n");

/***/ }),

/***/ "./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_features_settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./features-settings.vue?vue&type=script&lang=js& */ \"./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js&\");\n/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__[\"default\"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_features_settings_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[\"default\"]); //# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdmlld3MvYmFjay9hZG1pbi9zZXR0aW5ncy9sYW5kaW5nLXBhZ2UvZmVhdHVyZXMtc2V0dGluZ3MudnVlPzZjYWEiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBLHdDQUF5TixDQUFnQiw2UEFBRyxFQUFDIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL3ZpZXdzL2JhY2svYWRtaW4vc2V0dGluZ3MvbGFuZGluZy1wYWdlL2ZlYXR1cmVzLXNldHRpbmdzLnZ1ZT92dWUmdHlwZT1zY3JpcHQmbGFuZz1qcyYuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgbW9kIGZyb20gXCItIS4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9iYWJlbC1sb2FkZXIvbGliL2luZGV4LmpzPz9yZWYtLTQtMCEuLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvaW5kZXguanM/P3Z1ZS1sb2FkZXItb3B0aW9ucyEuL2ZlYXR1cmVzLXNldHRpbmdzLnZ1ZT92dWUmdHlwZT1zY3JpcHQmbGFuZz1qcyZcIjsgZXhwb3J0IGRlZmF1bHQgbW9kOyBleHBvcnQgKiBmcm9tIFwiLSEuLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvYmFiZWwtbG9hZGVyL2xpYi9pbmRleC5qcz8/cmVmLS00LTAhLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2luZGV4LmpzPz92dWUtbG9hZGVyLW9wdGlvbnMhLi9mZWF0dXJlcy1zZXR0aW5ncy52dWU/dnVlJnR5cGU9c2NyaXB0Jmxhbmc9anMmXCIiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=script&lang=js&\n");

/***/ }),

/***/ "./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec&":
/*!******************************************************************************************************************!*\
  !*** ./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec& ***!
  \******************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_features_settings_vue_vue_type_template_id_291b8aec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ref--6!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./features-settings.vue?vue&type=template&id=291b8aec& */ \"./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec&\");\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"render\", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_features_settings_vue_vue_type_template_id_291b8aec___WEBPACK_IMPORTED_MODULE_0__[\"render\"]; });\n\n/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, \"staticRenderFns\", function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ref_6_node_modules_vue_loader_lib_index_js_vue_loader_options_features_settings_vue_vue_type_template_id_291b8aec___WEBPACK_IMPORTED_MODULE_0__[\"staticRenderFns\"]; });\n\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvdmlld3MvYmFjay9hZG1pbi9zZXR0aW5ncy9sYW5kaW5nLXBhZ2UvZmVhdHVyZXMtc2V0dGluZ3MudnVlP2FhMGYiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBIiwiZmlsZSI6Ii4vcmVzb3VyY2VzL2pzL3ZpZXdzL2JhY2svYWRtaW4vc2V0dGluZ3MvbGFuZGluZy1wYWdlL2ZlYXR1cmVzLXNldHRpbmdzLnZ1ZT92dWUmdHlwZT10ZW1wbGF0ZSZpZD0yOTFiOGFlYyYuanMiLCJzb3VyY2VzQ29udGVudCI6WyJleHBvcnQgKiBmcm9tIFwiLSEuLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvYmFiZWwtbG9hZGVyL2xpYi9pbmRleC5qcz8/cmVmLS00LTAhLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL3Z1ZS1sb2FkZXIvbGliL2xvYWRlcnMvdGVtcGxhdGVMb2FkZXIuanM/P3JlZi0tNiEuLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvdnVlLWxvYWRlci9saWIvaW5kZXguanM/P3Z1ZS1sb2FkZXItb3B0aW9ucyEuL2ZlYXR1cmVzLXNldHRpbmdzLnZ1ZT92dWUmdHlwZT10ZW1wbGF0ZSZpZD0yOTFiOGFlYyZcIiJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/views/back/admin/settings/landing-page/features-settings.vue?vue&type=template&id=291b8aec&\n");

/***/ })

}]);