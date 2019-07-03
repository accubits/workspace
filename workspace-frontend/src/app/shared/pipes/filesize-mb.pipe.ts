import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'filesizeMB'
})
export class FilesizeMBPipe implements PipeTransform {

  transform(size: number, extension: string = 'MB'): any {
    if(size < 1048576){
      return Math.round((size/1024)) + 'KB';
    }else{
      return (size / (1024 * 1024)).toFixed(2) + extension;
    }
  }

}
