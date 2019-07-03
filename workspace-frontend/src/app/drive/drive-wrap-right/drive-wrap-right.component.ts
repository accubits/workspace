import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';
 
@Component({
  selector: 'app-drive-wrap-right',
  templateUrl: './drive-wrap-right.component.html',
  styleUrls: ['./drive-wrap-right.component.scss']
})
export class DriveWrapRightComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  constructor() { }

  ngOnInit() {
   }
}
