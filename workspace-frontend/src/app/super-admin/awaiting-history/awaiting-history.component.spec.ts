import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AwaitingHistoryComponent } from './awaiting-history.component';

describe('AwaitingHistoryComponent', () => {
  let component: AwaitingHistoryComponent;
  let fixture: ComponentFixture<AwaitingHistoryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AwaitingHistoryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AwaitingHistoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
