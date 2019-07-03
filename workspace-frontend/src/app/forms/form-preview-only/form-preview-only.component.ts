import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
import { DataService } from '../../shared/services/data.service';


@Component({
  selector: 'app-form-preview-only',
  templateUrl: './form-preview-only.component.html',
  styleUrls: ['./form-preview-only.component.scss']
})
export class FormPreviewOnlyComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;

  localElementArray: any = [];

  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

  /* Updating preview elements with new changes */
  updatePreviewElements(elements) {
    this.localElementArray = elements;
  }

  closePreviewOnly(): void {
    this.dataService.viewForm.previewOnlyShow = false;
  //   this.dataService.resetForm();
  //   this.dataService.resetFormView();
  }
}
