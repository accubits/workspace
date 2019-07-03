import { Component, OnInit } from '@angular/core';
import { Configs } from '../../../config';
import { DataService } from '../../../shared/services/data.service';


@Component({
  selector: 'app-file-upload-submit',
  templateUrl: './file-upload-submit.component.html',
  styleUrls: ['./file-upload-submit.component.scss']
})
export class FileUploadSubmitComponent implements OnInit {

  public assetUrl = Configs.assetBaseUrl;
  constructor(public dataService: DataService) { }

  ngOnInit() {
  }

}
