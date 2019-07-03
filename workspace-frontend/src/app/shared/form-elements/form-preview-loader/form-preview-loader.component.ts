import { Component, OnInit, Input } from '@angular/core';
import { DataService } from '../../services/data.service';

@Component({
  selector: 'app-form-preview-loader',
  templateUrl: './form-preview-loader.component.html',
  styleUrls: ['./form-preview-loader.component.scss']
})
export class FormPreviewLoaderComponent implements OnInit {

  @Input() data: any;
  currentElementIndex: string;
  localElementArray = <any>{ ...this.dataService.formElements };
  constructor(public dataService: DataService) { }

  ngOnInit() {
    setTimeout(() => {
      var clickedDetails = this.data.split(',');
      this.localElementArray[clickedDetails[0]] = true;
      this.currentElementIndex = clickedDetails[1];
      // console.log(clickedDetails);
    }, 100);
  }

  
}
