import { Component, OnInit } from '@angular/core';
import { Configs } from '../../config';

@Component({
  selector: 'app-chat-recent',
  templateUrl: './chat-recent.component.html',
  styleUrls: ['./chat-recent.component.scss']
})
export class ChatRecentComponent implements OnInit {
  public assetUrl = Configs.assetBaseUrl;
  constructor() { }

  ngOnInit() {
  }

}
