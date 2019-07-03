import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrExcellenceComponent } from './wr-excellence.component';

describe('WrExcellenceComponent', () => {
  let component: WrExcellenceComponent;
  let fixture: ComponentFixture<WrExcellenceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrExcellenceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrExcellenceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
