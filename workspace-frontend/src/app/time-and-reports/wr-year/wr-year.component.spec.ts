import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrYearComponent } from './wr-year.component';

describe('WrYearComponent', () => {
  let component: WrYearComponent;
  let fixture: ComponentFixture<WrYearComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrYearComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrYearComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
