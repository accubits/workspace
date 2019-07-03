import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrMonthComponent } from './wr-month.component';

describe('WrMonthComponent', () => {
  let component: WrMonthComponent;
  let fixture: ComponentFixture<WrMonthComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrMonthComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrMonthComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
