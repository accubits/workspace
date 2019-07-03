import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';

@Component({
  selector: 'app-chat-all',
  templateUrl: './chat-all.component.html',
  styleUrls: ['./chat-all.component.scss']
})
export class ChatAllComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  constructor() { }

  ngOnInit() {
  }

}
