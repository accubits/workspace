import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';

@Component({
  selector: 'app-form-preview',
  templateUrl: './form-preview.component.html',
  styleUrls: ['./form-preview.component.scss']
})
export class FormPreviewComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;

  localElementArray: any = [];

  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

  /* Updating preview elements with new changes */
  updatePreviewElements(elements) {
    this.localElementArray = elements;
  }

}
