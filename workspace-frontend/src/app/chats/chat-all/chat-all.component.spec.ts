import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ChatAllComponent } from './chat-all.component';

describe('ChatAllComponent', () => {
  let component: ChatAllComponent;
  let fixture: ComponentFixture<ChatAllComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ChatAllComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ChatAllComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
