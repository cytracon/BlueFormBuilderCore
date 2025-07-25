/*
 * jQuery File Upload Image Preview & Resize Plugin
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

/* global define, require */

(function (factory) {
  "use strict";
  if (typeof define === "function" && define.amd) {
    // Register as an anonymous AMD module:
    define([
      "jquery",
      "Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image",
      "Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-meta",
      "Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-scale",
      "Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-exif",
      "Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-orientation",
      "Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-canvas-to-blob/js/canvas-to-blob",
      "Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/jquery.fileupload-process",
    ], factory);
  } else if (typeof exports === "object") {
    // Node/CommonJS:
    factory(
      require("jquery"),
      require("Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image"),
      require("Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-meta"),
      require("Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-scale"),
      require("Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-exif"),
      require("Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-load-image/js/load-image-orientation"),
      require("Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/vendor/blueimp-canvas-to-blob/js/canvas-to-blob"),
      require("Cytracon_BlueFormBuilderCore/js/jquery/fileUploader/jquery.fileupload-process")
    );
  } else {
    // Browser globals:
    factory(window.jQuery, window.loadImage);
  }
})(function ($, loadImage) {
  "use strict";

  // Prepend to the default processQueue:
  $.blueimp.fileupload.prototype.options.processQueue.unshift(
    {
      action: "loadImageMetaData",
      maxMetaDataSize: "@",
      disableImageHead: "@",
      disableMetaDataParsers: "@",
      disableExif: "@",
      disableExifOffsets: "@",
      includeExifTags: "@",
      excludeExifTags: "@",
      disableIptc: "@",
      disableIptcOffsets: "@",
      includeIptcTags: "@",
      excludeIptcTags: "@",
      disabled: "@disableImageMetaDataLoad",
    },
    {
      action: "loadImage",
      // Use the action as prefix for the "@" options:
      prefix: true,
      fileTypes: "@",
      maxFileSize: "@",
      noRevoke: "@",
      disabled: "@disableImageLoad",
    },
    {
      action: "resizeImage",
      // Use "image" as prefix for the "@" options:
      prefix: "image",
      maxWidth: "@",
      maxHeight: "@",
      minWidth: "@",
      minHeight: "@",
      crop: "@",
      orientation: "@",
      forceResize: "@",
      disabled: "@disableImageResize",
    },
    {
      action: "saveImage",
      quality: "@imageQuality",
      type: "@imageType",
      disabled: "@disableImageResize",
    },
    {
      action: "saveImageMetaData",
      disabled: "@disableImageMetaDataSave",
    },
    {
      action: "resizeImage",
      // Use "preview" as prefix for the "@" options:
      prefix: "preview",
      maxWidth: "@",
      maxHeight: "@",
      minWidth: "@",
      minHeight: "@",
      crop: "@",
      orientation: "@",
      thumbnail: "@",
      canvas: "@",
      disabled: "@disableImagePreview",
    },
    {
      action: "setImage",
      name: "@imagePreviewName",
      disabled: "@disableImagePreview",
    },
    {
      action: "deleteImageReferences",
      disabled: "@disableImageReferencesDeletion",
    }
  );

  // The File Upload Resize plugin extends the fileupload widget
  // with image resize functionality:
  $.widget("blueimp.fileupload", $.blueimp.fileupload, {
    options: {
      // The regular expression for the types of images to load:
      // matched against the file type:
      loadImageFileTypes: /^image\/(gif|jpeg|png|svg\+xml)$/,
      // The maximum file size of images to load:
      loadImageMaxFileSize: 10000000, // 10MB
      // The maximum width of resized images:
      imageMaxWidth: 1920,
      // The maximum height of resized images:
      imageMaxHeight: 1080,
      // Defines the image orientation (1-8) or takes the orientation
      // value from Exif data if set to true:
      imageOrientation: true,
      // Define if resized images should be cropped or only scaled:
      imageCrop: false,
      // Disable the resize image functionality by default:
      disableImageResize: true,
      // The maximum width of the preview images:
      previewMaxWidth: 80,
      // The maximum height of the preview images:
      previewMaxHeight: 80,
      // Defines the preview orientation (1-8) or takes the orientation
      // value from Exif data if set to true:
      previewOrientation: true,
      // Create the preview using the Exif data thumbnail:
      previewThumbnail: true,
      // Define if preview images should be cropped or only scaled:
      previewCrop: false,
      // Define if preview images should be resized as canvas elements:
      previewCanvas: true,
    },

    processActions: {
      // Loads the image given via data.files and data.index
      // as img element, if the browser supports the File API.
      // Accepts the options fileTypes (regular expression)
      // and maxFileSize (integer) to limit the files to load:
      loadImage: function (data, options) {
        if (options.disabled) {
          return data;
        }
        var that = this,
          file = data.files[data.index],
          // eslint-disable-next-line new-cap
          dfd = $.Deferred();
        if (
          ($.type(options.maxFileSize) === "number" &&
            file.size > options.maxFileSize) ||
          (options.fileTypes && !options.fileTypes.test(file.type)) ||
          !loadImage(
            file,
            function (img) {
              if (img.src) {
                data.img = img;
              }
              dfd.resolveWith(that, [data]);
            },
            options
          )
        ) {
          return data;
        }
        return dfd.promise();
      },

      // Resizes the image given as data.canvas or data.img
      // and updates data.canvas or data.img with the resized image.
      // Also stores the resized image as preview property.
      // Accepts the options maxWidth, maxHeight, minWidth,
      // minHeight, canvas and crop:
      resizeImage: function (data, options) {
        if (options.disabled || !(data.canvas || data.img)) {
          return data;
        }
        // eslint-disable-next-line no-param-reassign
        options = $.extend({ canvas: true }, options);
        var that = this,
          // eslint-disable-next-line new-cap
          dfd = $.Deferred(),
          img = (options.canvas && data.canvas) || data.img,
          resolve = function (newImg) {
            if (
              newImg &&
              (newImg.width !== img.width ||
                newImg.height !== img.height ||
                options.forceResize)
            ) {
              data[newImg.getContext ? "canvas" : "img"] = newImg;
            }
            data.preview = newImg;
            dfd.resolveWith(that, [data]);
          },
          thumbnail,
          thumbnailBlob;
        if (data.exif && options.thumbnail) {
          thumbnail = data.exif.get("Thumbnail");
          thumbnailBlob = thumbnail && thumbnail.get("Blob");
          if (thumbnailBlob) {
            options.orientation = data.exif.get("Orientation");
            loadImage(thumbnailBlob, resolve, options);
            return dfd.promise();
          }
        }
        if (data.orientation) {
          // Prevent orienting the same image twice:
          delete options.orientation;
        } else {
          data.orientation = options.orientation || loadImage.orientation;
        }
        if (img) {
          resolve(loadImage.scale(img, options, data));
          return dfd.promise();
        }
        return data;
      },

      // Saves the processed image given as data.canvas
      // inplace at data.index of data.files:
      saveImage: function (data, options) {
        if (!data.canvas || options.disabled) {
          return data;
        }
        var that = this,
          file = data.files[data.index],
          // eslint-disable-next-line new-cap
          dfd = $.Deferred();
        if (data.canvas.toBlob) {
          data.canvas.toBlob(
            function (blob) {
              if (!blob.name) {
                if (file.type === blob.type) {
                  blob.name = file.name;
                } else if (file.name) {
                  blob.name = file.name.replace(
                    /\.\w+$/,
                    "." + blob.type.substr(6)
                  );
                }
              }
              // Don't restore invalid meta data:
              if (file.type !== blob.type) {
                delete data.imageHead;
              }
              // Store the created blob at the position
              // of the original file in the files list:
              data.files[data.index] = blob;
              dfd.resolveWith(that, [data]);
            },
            options.type || file.type,
            options.quality
          );
        } else {
          return data;
        }
        return dfd.promise();
      },

      loadImageMetaData: function (data, options) {
        if (options.disabled) {
          return data;
        }
        var that = this,
          // eslint-disable-next-line new-cap
          dfd = $.Deferred();
        loadImage.parseMetaData(
          data.files[data.index],
          function (result) {
            $.extend(data, result);
            dfd.resolveWith(that, [data]);
          },
          options
        );
        return dfd.promise();
      },

      saveImageMetaData: function (data, options) {
        if (
          !(
            data.imageHead &&
            data.canvas &&
            data.canvas.toBlob &&
            !options.disabled
          )
        ) {
          return data;
        }
        var that = this,
          file = data.files[data.index],
          // eslint-disable-next-line new-cap
          dfd = $.Deferred();
        if (data.orientation === true && data.exifOffsets) {
          // Reset Exif Orientation data:
          loadImage.writeExifData(data.imageHead, data, "Orientation", 1);
        }
        loadImage.replaceHead(file, data.imageHead, function (blob) {
          blob.name = file.name;
          data.files[data.index] = blob;
          dfd.resolveWith(that, [data]);
        });
        return dfd.promise();
      },

      // Sets the resized version of the image as a property of the
      // file object, must be called after "saveImage":
      setImage: function (data, options) {
        if (data.preview && !options.disabled) {
          data.files[data.index][options.name || "preview"] = data.preview;
        }
        return data;
      },

      deleteImageReferences: function (data, options) {
        if (!options.disabled) {
          delete data.img;
          delete data.canvas;
          delete data.preview;
          delete data.imageHead;
        }
        return data;
      },
    },
  });
});
