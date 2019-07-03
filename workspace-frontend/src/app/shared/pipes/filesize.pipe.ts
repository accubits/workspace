import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'filesize'
})
export class FilesizePipe implements PipeTransform {

  transform(value: any, args?: any): any {
    if(value < 1048576){
      return Math.round((value/1024)).toString() + ' KB';
    }else{
      return Math.round((value/1024)/1024).toString() + ' MB';
    }
  }

}
