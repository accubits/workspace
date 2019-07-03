import { Pipe, PipeTransform } from '@angular/core';
import { Configs } from '../../config';

@Pipe({
  name: 'imageextension'
})
export class ImageextensionPipe implements PipeTransform {

  transform(value: any, args?: any): any {
    let extension = value.split('.')[value.split('.').length - 1];
    if (value.split('.').length > 1) {
      if (extension === 'png' || extension === 'jpeg' || extension === 'jpg' || extension === 'PNG' || extension === 'JPEG' || extension === 'JPG') {
        return Configs.assetBaseUrl + 'assets/images/drive/img.png'
      }
      else if (extension === 'pdf' || extension === 'ppt' || extension === 'mp3' || extension === 'mp4') {
        return Configs.assetBaseUrl + 'assets/images/drive/' + extension + '.png'
      }
      else {
        return Configs.assetBaseUrl + 'assets/images/drive/noExtension.png'
      }
    }
    else {
      return Configs.assetBaseUrl + 'assets/images/drive/noExtension.png'
    }
  }
}
