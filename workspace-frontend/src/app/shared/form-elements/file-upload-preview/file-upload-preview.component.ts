import { Component, OnInit,Input } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-file-upload-preview',
  templateUrl: './file-upload-preview.component.html',
  styleUrls: ['./file-upload-preview.component.scss']
})
export class FileUploadPreviewComponent implements OnInit {

  @Input() data: any;
  currentElementIndex: string;

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }
  /* Data model for file element */
  fileElement =   {
    componentId:null,
    action: 'create',
    type: 'fileUpload',
    fileUpload: {
      label: '',
      isRequired: false
    }
 }

 currentElement:{}

  ngOnInit() {
    setTimeout(() => {
      this.currentElementIndex = this.data;
      this.currentElement = this.dataService.formElementArray.filter(
        element => element.index === this.currentElementIndex)[0];
      this.fileElement = this.currentElement['element']
      console.log(this.dataService.formElementToggle.activeIndex);
      console.log(this.currentElementIndex);
    }, 100);
  }


}
