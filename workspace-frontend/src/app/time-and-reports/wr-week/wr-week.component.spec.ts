import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrWeekComponent } from './wr-week.component';

describe('WrWeekComponent', () => {
  let component: WrWeekComponent;
  let fixture: ComponentFixture<WrWeekComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrWeekComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrWeekComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
