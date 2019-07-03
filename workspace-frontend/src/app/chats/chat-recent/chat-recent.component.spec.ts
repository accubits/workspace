import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatRecentComponent } from './chat-recent.component';

describe('ChatRecentComponent', () => {
  let component: ChatRecentComponent;
  let fixture: ComponentFixture<ChatRecentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatRecentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatRecentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
