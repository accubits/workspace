import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ExpiredHistoryTabComponent } from './expired-history-tab.component';

describe('ExpiredHistoryTabComponent', () => {
  let component: ExpiredHistoryTabComponent;
  let fixture: ComponentFixture<ExpiredHistoryTabComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ExpiredHistoryTabComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ExpiredHistoryTabComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
