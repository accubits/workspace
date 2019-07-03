import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbMonthComponent } from './ab-month.component';

describe('AbMonthComponent', () => {
  let component: AbMonthComponent;
  let fixture: ComponentFixture<AbMonthComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbMonthComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbMonthComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
